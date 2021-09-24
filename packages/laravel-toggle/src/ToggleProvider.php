<?php

declare(strict_types=1);

namespace Pheature\Community\Laravel;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Nyholm\Psr7\Factory\Psr17Factory;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\ChainToggleStrategyFactoryFactory;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\DeleteFeatureFactory;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatureFactory;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\GetFeaturesFactory;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PatchFeatureFactory;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Crud\Psr7\Toggle\PostFeatureFactory;
use Pheature\Dbal\Toggle\Cli\InitSchema;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\SegmentFactoryFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrategyFactoryFactory;
use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\CommandRunnerFactory;
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
        /** @var null|array<string, mixed>  $configItem */
        $configItem = config('pheature_flags');
        if (null === $configItem) {
            return;
        }
        $toggleConfig = new ToggleConfig($configItem);
        $this->app->bind(ToggleConfig::class, fn() => $toggleConfig);
        $this->app->bind(StrategyFactory::class, Closure::fromCallable(new StrategyFactoryFactory()));
        $this->app->bind(SegmentFactory::class, Closure::fromCallable(new SegmentFactoryFactory()));
        foreach ($toggleConfig->strategyTypes() as $strategyType) {
            $this->app->bind($strategyType['type'], $strategyType['factory_id']);
        }
        foreach ($toggleConfig->segmentTypes() as $segmentType) {
            $this->app->bind($segmentType['type'], $segmentType['factory_id']);
        }
        $this->app->bind(
            ChainToggleStrategyFactory::class,
            Closure::fromCallable(new ChainToggleStrategyFactoryFactory())
        );
        $this->app->bind(ResponseFactoryInterface::class, static fn() => new Psr17Factory());
        $this->app->bind(FeatureRepository::class, Closure::fromCallable(new FeatureRepositoryFactory()));
        $this->app->bind(FeatureFinder::class, Closure::fromCallable(new FeatureFinderFactory()));
        $this->app->extend(
            ServerRequestInterface::class,
            Closure::fromCallable(new RouteParameterAsPsr7RequestAttribute($this->app))
        );

        $this->enableSDK();
        $this->enableAPI($toggleConfig);
        $this->enableDBAL();
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

    private function enableAPI(ToggleConfig $toggleConfig): void
    {
        if ($toggleConfig->apiEnabled()) {
            $this->app->bind(GetFeature::class, Closure::fromCallable(new GetFeatureFactory()));
            $this->app->bind(GetFeatures::class, Closure::fromCallable(new GetFeaturesFactory()));
            $this->app->bind(PostFeature::class, Closure::fromCallable(new PostFeatureFactory()));
            $this->app->bind(PatchFeature::class, Closure::fromCallable(new PatchFeatureFactory()));
            $this->app->bind(DeleteFeature::class, Closure::fromCallable(new DeleteFeatureFactory()));
        }
    }

    private function enableSDK(): void
    {
        if (class_exists(CommandRunner::class)) {
            $this->app->bind(CommandRunner::class, Closure::fromCallable(new CommandRunnerFactory()));
        }
    }

    private function enableDBAL(): void
    {
        if ('dbal' === config('pheature_flags.driver')) {
            $this->commands([InitSchema::class]);
        }
    }
}
