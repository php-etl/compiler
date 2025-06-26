<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;

final readonly class ComposerAutoload implements Dockerfile\LayerInterface, \Stringable
{
    public function __construct(
        /**
         * @param array<string, array<string, string|list<string>>> $autoloads
         */
        private array $autoloads
    ) {
    }

    private static function command(string ...$command): string
    {
        return implode(' ', array_map(fn ($argument) => self::escapeArgument($argument), $command));
    }

    /**
     * Escapes a string to be used as a shell argument.
     */
    private static function escapeArgument(?string $argument): string
    {
        if ('' === $argument || null === $argument) {
            return '""';
        }
        if ('\\' !== \DIRECTORY_SEPARATOR) {
            return "'".str_replace("'", "'\\''", $argument)."'";
        }
        if (str_contains($argument, "\0")) {
            $argument = str_replace("\0", '?', $argument);
        }
        if (!preg_match('/[\/()%!^"<>&|\s]/', $argument)) {
            return $argument;
        }
        $argument = preg_replace('/(\\\\+)$/', '$1$1', $argument);

        return '"'.str_replace(['"', '^', '%', '!', "\n"], ['""', '"^^"', '"^%"', '"^!"', '!LF!'], $argument).'"';
    }

    private static function pipe(string ...$commands): string
    {
        return implode(' | ', $commands);
    }

    private static function indirection(string $command, string $destination): string
    {
        return $command.' > '.self::escapeArgument($destination);
    }

    private static function and(string ...$commands): string
    {
        return implode(' \\'.\PHP_EOL.'    && ', $commands);
    }

    public function __toString(): string
    {
        if (\count($this->autoloads) <= 0) {
            return '';
        }

        $commands = self::and(
            ...array_map(
                fn ($type, $autoload) => match ($type) {
                    'psr4' => self::and(
                        self::pipe(
                            self::command('cat', 'composer.json'),
                            self::command('jq', '--indent', '4', sprintf('.autoload."psr-4" |= . + %s', json_encode($autoload, \JSON_THROW_ON_ERROR))),
                            self::command('tee', 'composer.temp')
                        ),
                        self::indirection(self::command('cat', 'composer.temp'), 'composer.json'),
                        self::command('rm', 'composer.temp'),
                    )
                },
                array_keys($this->autoloads),
                array_values($this->autoloads)
            )
        );

        return (string) new Dockerfile\Run(
            <<<RUN
                set -ex \\
                    && {$commands}
                RUN
        );
    }
}
