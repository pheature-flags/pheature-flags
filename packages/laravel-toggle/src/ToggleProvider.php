<?php

declare(strict_types=1);

namespace Pheature\Community\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Nyholm\Psr7\Factory\Psr17Factory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Dbal\Toggle\Cli\InitSchema;
use Pheature\Sdk\CommandRunner;
use Psr\Container\ContainerInterface;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ToggleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            ToggleConfig::class,
            function (): ToggleConfig {
                /** @var array<string, mixed> $config */
                $config = config('pheature_flags');
                return new ToggleConfig($config);
            }
        );

        $this->app->bind(
            ResponseFactoryInterface::class,
            function () {
                return new Psr17Factory();
            }
        );

        $this->app->bind(
            FeatureRepository::class,
            function (ContainerInterface $container): FeatureRepository {
                $factory = new FeatureRepositoryFactory();
                return $factory($container);
            }
        );

        $this->app->bind(
            FeatureFinder::class,
            function (ContainerInterface $container): FeatureFinder {
                $factory = new FeatureFinderFactory();
                return $factory($container);
            }
        );

        $this->app->bind(
            CommandRunner::class,
            function (ContainerInterface $container): CommandRunner {
                /** @var FeatureFinder $featureFinder */
                $featureFinder = $container->get(FeatureFinder::class);
                return new CommandRunner(new Toggle($featureFinder));
            }
        );

        $this->app->extend(
            ServerRequestInterface::class,
            function (ServerRequestInterface $psrRequest) {
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
        );

        if ('dbal' === config('pheature_flags.driver')) {
            $this->commands(
                [
                InitSchema::class
                ]
            );
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes(
            [
            __DIR__ . '/../config/pheature_flags.php' => config_path('pheature_flags.php'),
            ],
            'config'
        );

        Route::group(
            $this->routeConfiguration(),
            function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/pheature_flags.php');
            }
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function routeConfiguration(): array
    {
        return [
            'prefix' => config('pheature_flags.api_prefix') ?? '',
            'middleware' => config('pheature_flags.middleware') ?? ['api'],
        ];
    }
}
