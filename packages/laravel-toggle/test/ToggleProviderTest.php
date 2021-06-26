<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Laravel;

use Doctrine\DBAL\Connection;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;
use Pheature\Community\Laravel\ToggleProvider;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Dbal\Toggle\Read\DbalFeatureFinder;
use Pheature\Dbal\Toggle\Write\DbalFeatureRepository;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Pheature\InMemory\Toggle\InMemoryFeatureRepository;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\InCollectionMatchingSegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;
use Pheature\Sdk\CommandRunner;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;

final class ToggleProviderTest extends TestCase
{
    private const ROUTES = [
        'get_features',
        'get_feature',
        'post_feature',
        'patch_feature',
        'delete_feature',
    ];
    private Application $app;

    protected function setUp(): void
    {
        $this->app = Application::getInstance();
        $this->app->bind('config', function () {
            return new Repository([
                'pheature_flags' => require __DIR__ . '/../config/pheature_flags.php'
            ]);
        });
        $this->app->bind(Connection::class, fn() => null);
        $this->app->bind('files', function () {
            return $this->createMock(Filesystem::class);
        });
        Facade::setFacadeApplication($this->app);
        $this->app->boot();
        $this->app->registerCoreContainerAliases();
    }

    public function testItShouldRegisterPheatureFlagsServicesIncludingDbal(): void
    {
        $this->app->bind(Connection::class, fn() => $this->createMock(Connection::class));
        $this->app->bind('config', function () {
            return new Repository([
                'pheature_flags' => array_merge(require __DIR__ . '/../config/pheature_flags.php', [
                    'driver' => 'dbal'
                ])
            ]);
        });
        $serviceProvider = new ToggleProvider($this->app);
        $serviceProvider->register();

        self::assertInstanceOf(StrategyFactory::class, $this->app->get(StrategyFactory::class));
        self::assertInstanceOf(StrategyFactory::class, $this->app->get(EnableByMatchingSegment::NAME));
        self::assertInstanceOf(StrategyFactory::class, $this->app->get(EnableByMatchingIdentityId::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(SegmentFactory::class));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(StrictMatchingSegment::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(InCollectionMatchingSegment::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(IdentitySegment::NAME));
        self::assertInstanceOf(ChainToggleStrategyFactory::class, $this->app->get(ChainToggleStrategyFactory::class));
        self::assertInstanceOf(ResponseFactoryInterface::class, $this->app->get(ResponseFactoryInterface::class));
        self::assertInstanceOf(DbalFeatureRepository::class, $this->app->get(FeatureRepository::class));
        self::assertInstanceOf(DbalFeatureFinder::class, $this->app->get(FeatureFinder::class));
        self::assertInstanceOf(CommandRunner::class, $this->app->get(CommandRunner::class));
    }

    public function testItShouldRegisterPheatureFlagsServicesIncludingAPIenabled(): void
    {
        $this->app->bind(Connection::class, fn() => $this->createMock(Connection::class));
        $this->app->bind('config', function () {
            return new Repository([
                'pheature_flags' => array_merge(require __DIR__ . '/../config/pheature_flags.php', [
                    'driver' => 'dbal',
                    'api_enabled' => true,
                ])
            ]);
        });
        $serviceProvider = new ToggleProvider($this->app);
        $serviceProvider->register();

        self::assertInstanceOf(StrategyFactory::class, $this->app->get(StrategyFactory::class));
        self::assertInstanceOf(StrategyFactory::class, $this->app->get(EnableByMatchingSegment::NAME));
        self::assertInstanceOf(StrategyFactory::class, $this->app->get(EnableByMatchingIdentityId::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(SegmentFactory::class));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(StrictMatchingSegment::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(InCollectionMatchingSegment::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(IdentitySegment::NAME));
        self::assertInstanceOf(ChainToggleStrategyFactory::class, $this->app->get(ChainToggleStrategyFactory::class));
        self::assertInstanceOf(ResponseFactoryInterface::class, $this->app->get(ResponseFactoryInterface::class));
        self::assertInstanceOf(DbalFeatureRepository::class, $this->app->get(FeatureRepository::class));
        self::assertInstanceOf(DbalFeatureFinder::class, $this->app->get(FeatureFinder::class));
        self::assertInstanceOf(GetFeature::class, $this->app->get(GetFeature::class));
        self::assertInstanceOf(GetFeatures::class, $this->app->get(GetFeatures::class));
        self::assertInstanceOf(PostFeature::class, $this->app->get(PostFeature::class));
        self::assertInstanceOf(PatchFeature::class, $this->app->get(PatchFeature::class));
        self::assertInstanceOf(DeleteFeature::class, $this->app->get(DeleteFeature::class));
        self::assertInstanceOf(CommandRunner::class, $this->app->get(CommandRunner::class));
    }

    public function testItShouldRegisterPheatureFlagsDefaultServices(): void
    {
        $serviceProvider = new ToggleProvider($this->app);
        $serviceProvider->register();

        self::assertInstanceOf(StrategyFactory::class, $this->app->get(StrategyFactory::class));
        self::assertInstanceOf(StrategyFactory::class, $this->app->get(EnableByMatchingSegment::NAME));
        self::assertInstanceOf(StrategyFactory::class, $this->app->get(EnableByMatchingIdentityId::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(SegmentFactory::class));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(StrictMatchingSegment::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(InCollectionMatchingSegment::NAME));
        self::assertInstanceOf(SegmentFactory::class, $this->app->get(IdentitySegment::NAME));
        self::assertInstanceOf(ChainToggleStrategyFactory::class, $this->app->get(ChainToggleStrategyFactory::class));
        self::assertInstanceOf(ResponseFactoryInterface::class, $this->app->get(ResponseFactoryInterface::class));
        self::assertInstanceOf(InMemoryFeatureRepository::class, $this->app->get(FeatureRepository::class));
        self::assertInstanceOf(InMemoryFeatureFinder::class, $this->app->get(FeatureFinder::class));
        self::assertInstanceOf(CommandRunner::class, $this->app->get(CommandRunner::class));
    }

    public function testItShouldBootPheatureFlagsRoutesAndConfig(): void
    {
        $serviceProvider = new ToggleProvider($this->app);
        $serviceProvider->boot();

        $routes = Route::getRoutes()->getRoutes();
        self::assertCount(5, $routes);
        $addedRoutes = [];
        foreach ($routes as $route) {
            $addedRoutes[] = $route->getName();
        }
        self::assertSame(self::ROUTES, $addedRoutes);

    }
}
