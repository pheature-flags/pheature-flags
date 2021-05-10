<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Toggle\Transformer;

use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response;

class NyholmPsrToSymfonyResponseTransformer implements PsrToSymfonyResponseTransformer
{
    public function transform(ResponseInterface $psrResponse): Response
    {
        $httpFoundationFactory = new HttpFoundationFactory();

        return $httpFoundationFactory->createResponse($psrResponse);
    }
}
