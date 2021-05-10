<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Controller;

use Pheature\Community\Symfony\Toggle\Transformer\PsrToSymfonyResponseTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\SymfonyToPsrRequestTransformer;
use Pheature\Crud\Psr7\Toggle\PatchFeature as PsrPatchFeatures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatchFeature
{
    private SymfonyToPsrRequestTransformer $requestTransformer;
    private PsrToSymfonyResponseTransformer $responseTransformer;
    private PsrPatchFeatures $patchFeature;

    public function __construct(
        SymfonyToPsrRequestTransformer $requestTransformer,
        PsrToSymfonyResponseTransformer $responseTransformer,
        PsrPatchFeatures $patchFeature
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->responseTransformer = $responseTransformer;
        $this->patchFeature = $patchFeature;
    }

    public function __invoke(Request $symfonyRequest): Response
    {
        $psrRequest = $this->requestTransformer->transform($symfonyRequest);

        $psrResponse = $this->patchFeature->handle($psrRequest);

        return $this->responseTransformer->transform($psrResponse);
    }
}
