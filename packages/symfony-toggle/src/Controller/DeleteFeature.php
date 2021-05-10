<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Controller;

use Pheature\Community\Symfony\Toggle\Transformer\PsrToSymfonyResponseTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\SymfonyToPsrRequestTransformer;
use Pheature\Crud\Psr7\Toggle\DeleteFeature as PsrDeleteFeature;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteFeature
{
    private SymfonyToPsrRequestTransformer $requestTransformer;
    private PsrToSymfonyResponseTransformer $responseTransformer;
    private PsrDeleteFeature $deleteFeature;

    public function __construct(
        SymfonyToPsrRequestTransformer $requestTransformer,
        PsrToSymfonyResponseTransformer $responseTransformer,
        PsrDeleteFeature $deleteFeature
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->responseTransformer = $responseTransformer;
        $this->deleteFeature = $deleteFeature;
    }

    public function __invoke(Request $symfonyRequest): Response
    {
        $psrRequest = $this->requestTransformer->transform($symfonyRequest);

        $psrResponse = $this->deleteFeature->handle($psrRequest);

        return $this->responseTransformer->transform($psrResponse);
    }
}
