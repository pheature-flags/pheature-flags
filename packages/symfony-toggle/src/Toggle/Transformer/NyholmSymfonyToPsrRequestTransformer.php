<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Toggle\Transformer;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;

class NyholmSymfonyToPsrRequestTransformer implements SymfonyToPsrRequestTransformer
{
    public function transform(Request $symfonyRequest): ServerRequestInterface
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        return $psrHttpFactory->createRequest($symfonyRequest);
    }
}
