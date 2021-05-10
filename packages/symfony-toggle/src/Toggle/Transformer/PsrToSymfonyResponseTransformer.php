<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Toggle\Transformer;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

interface PsrToSymfonyResponseTransformer
{
    public function transform(ResponseInterface $psrResponse): Response;
}
