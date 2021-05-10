<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Controller;

use Pheature\Community\Symfony\Toggle\Transformer\PsrToSymfonyResponseTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\SymfonyToPsrRequestTransformer;
use Pheature\Crud\Psr7\Toggle\GetFeature as PsrGetFeature;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetFeature
{
    private SymfonyToPsrRequestTransformer $requestTransformer;
    private PsrToSymfonyResponseTransformer $responseTransformer;
    private PsrGetFeature $getFeature;

    public function __construct(
        SymfonyToPsrRequestTransformer $requestTransformer,
        PsrToSymfonyResponseTransformer $responseTransformer,
        PsrGetFeature $getFeature
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->responseTransformer = $responseTransformer;
        $this->getFeature = $getFeature;
    }

    public function __invoke(Request $symfonyRequest): Response
    {
        $psrRequest = $this->requestTransformer->transform($symfonyRequest);

        $psrResponse = $this->getFeature->handle($psrRequest);

        return $this->responseTransformer->transform($psrResponse);
    }
}
