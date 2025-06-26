<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock;

use Laminas\Diactoros\Request;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

final class RequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface
    {
        return new Request($uri, $method);
    }
}
