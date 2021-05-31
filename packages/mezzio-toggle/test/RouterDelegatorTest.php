<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Mezzio;

use Generator;
use Mezzio\Application;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Pheature\Community\Mezzio\RouterDelegator;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Test\Community\Mezzio\Fixtures\TestContainerFactory;
use Pheature\Test\Crud\Psr11\Toggle\Fixtures\PheatureFlagsConfig;
use Pheature\Test\Crud\Psr11\Toggle\Fixtures\TestToggleConfigFactory;
use PHPUnit\Framework\TestCase;

final class RouterDelegatorTest extends TestCase
{
    public function apiPrefixProvider(): Generator
    {
        yield 'empty prefix' => [''];
        yield 'with a prefix' => ['feature-flags'];
    }

    /** @dataProvider apiPrefixProvider */
    public function testItShouldCreateTheApplicationWithApiEndpoints(string $apiPrefix): void
    {
        $config = PheatureFlagsConfig::createDefault()
            ->withApiEnabled(true)
            ->withApiPrefix($apiPrefix)
            ->build();
        $toggleConfig = TestToggleConfigFactory::create($config);
        $container = TestContainerFactory::create([ToggleConfig::class => $toggleConfig]);

        $application = $this->createMock(Application::class);
        $application
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(
                [...$this->expectedRoutes($apiPrefix)['get_features']],
                [...$this->expectedRoutes($apiPrefix)['get_feature']],
            );
        $application->expects(self::once())->method('post')->with(...$this->expectedRoutes($apiPrefix)['post_feature']);
        $application->expects(self::once())->method('patch')->with(...$this->expectedRoutes($apiPrefix)['patch_feature']);
        $application->expects(self::once())->method('delete')->with(...$this->expectedRoutes($apiPrefix)['delete_feature']);

        $callback = static fn () => $application;

        $routerDelegator = new RouterDelegator();
        $routerDelegator->__invoke($container, 'some_service_name', $callback);
    }

    public function testItShouldNotCreateTheApplicationWithApiEndpoints(): void
    {
        $config = PheatureFlagsConfig::createDefault()
            ->withApiEnabled(false)
            ->build();
        $toggleConfig = TestToggleConfigFactory::create($config);
        $container = TestContainerFactory::create([ToggleConfig::class => $toggleConfig]);

        $application = $this->createMock(Application::class);
        $application->expects(self::never())->method('get');
        $application->expects(self::never())->method('post');
        $application->expects(self::never())->method('patch');
        $application->expects(self::never())->method('delete');

        $callback = static fn () => $application;

        $routerDelegator = new RouterDelegator();
        $routerDelegator->__invoke($container, 'some_service_name', $callback);
    }

    private function expectedRoutes(string $prefix): array
    {
        $prefix = '' === $prefix ? '' : sprintf('/%s', $prefix);

        return [
           'get_features' => [sprintf('%s/features', $prefix), [GetFeatures::class], 'get_features'],
           'get_feature' => [sprintf('%s/features/{feature_id}', $prefix), [GetFeature::class], 'get_feature'],
           'post_feature' => [sprintf('%s/features/{feature_id}', $prefix), [PostFeature::class], 'post_feature'],
           'patch_feature' => [sprintf('%s/features/{feature_id}', $prefix), [BodyParamsMiddleware::class, PatchFeature::class], 'patch_feature'],
           'delete_feature' => [sprintf('%s/features/{feature_id}', $prefix), [DeleteFeature::class], 'delete_feature'],
        ];
    }
}
