<?php

declare(strict_types=1);

namespace Pheature\Community\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

final class RouteParameterAsPsr7RequestAttribute
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function __invoke(ServerRequestInterface $psrRequest): ServerRequestInterface
    {
        /** @var Request $request */
        $request = $this->app->make('request');
        /** @var ?\Illuminate\Routing\Route $route */
        $route = $request->route();
        if ($route) {
            $parameters = $route->parameters();
            /**
             * @var string $key
             * @var mixed $value
             */
            foreach ($parameters as $key => $value) {
                $psrRequest = $psrRequest->withAttribute($key, $value);
            }
        }
        return $psrRequest;
    }
}
