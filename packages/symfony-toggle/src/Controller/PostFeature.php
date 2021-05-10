<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\Controller;

use Pheature\Community\Symfony\Toggle\Transformer\PsrToSymfonyResponseTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\SymfonyToPsrRequestTransformer;
use Pheature\Crud\Psr7\Toggle\PostFeature as PsrPostFeatures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostFeature
{
    private SymfonyToPsrRequestTransformer $requestTransformer;
    private PsrToSymfonyResponseTransformer $responseTransformer;
    private PsrPostFeatures $postFeature;

    public function __construct(
        SymfonyToPsrRequestTransformer $requestTransformer,
        PsrToSymfonyResponseTransformer $responseTransformer,
        PsrPostFeatures $postFeature
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->responseTransformer = $responseTransformer;
        $this->postFeature = $postFeature;
    }

    public function __invoke(Request $symfonyRequest): Response
    {
        $psrRequest = $this->requestTransformer->transform($symfonyRequest);

        $psrResponse = $this->postFeature->handle($psrRequest);

        return $this->responseTransformer->transform($psrResponse);
    }
}
