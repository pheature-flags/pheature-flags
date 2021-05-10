<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Toggle\Transformer;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface SymfonyToPsrRequestTransformer
{
    public function transform(Request $symfonyRequest): ServerRequestInterface;
}
