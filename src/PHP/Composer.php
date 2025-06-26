<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;

final class Composer implements Dockerfile\LayerInterface, \Stringable
{
    public function __toString(): string
    {
        return (string) new Dockerfile\Run(
            <<<'RUN'
                EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)" \
                && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
                && ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" \
                && if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then >&2 echo 'ERROR: Invalid installer signature'; rm composer-setup.php; exit 1; fi \
                && php composer-setup.php --install-dir /usr/local/bin --filename composer --2 \
                && php -r "unlink('composer-setup.php');" \
                && apk add jq
                RUN
        );
    }
}
