<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Controller;

use Pheature\Community\Symfony\Toggle\Transformer\PsrToSymfonyResponseTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\SymfonyToPsrRequestTransformer;
use Pheature\Crud\Psr7\Toggle\GetFeatures as PsrGetFeatures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetFeatures
{
    private SymfonyToPsrRequestTransformer $requestTransformer;
    private PsrToSymfonyResponseTransformer $responseTransformer;
    private PsrGetFeatures $getFeatures;

    public function __construct(
        SymfonyToPsrRequestTransformer $requestTransformer,
        PsrToSymfonyResponseTransformer $responseTransformer,
        PsrGetFeatures $getFeatures
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->responseTransformer = $responseTransformer;
        $this->getFeatures = $getFeatures;
    }

    public function __invoke(Request $symfonyRequest): Response
    {
        $psrRequest = $this->requestTransformer->transform($symfonyRequest);

        $psrResponse = $this->getFeatures->handle($psrRequest);

        return $this->responseTransformer->transform($psrResponse);
    }
}
