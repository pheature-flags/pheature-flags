<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\DependencyInjection;

use Pheature\Community\Symfony\DependencyInjection\ToggleAPIPass;
use Pheature\Test\Crud\Psr11\Toggle\Fixtures\PheatureFlagsConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

final class ToggleAPIPassTest extends TestCase
{
    public function testItShouldNotLoadApiServicesInContainer(): void
    {
        $config = PheatureFlagsConfig::createDefault()
            ->withApiEnabled(false)
            ->build();

        $compilerPass = new ToggleAPIPass();
        $container = TestContainerFactory::create($compilerPass, null, $config);

        $this->expectException(ParameterNotFoundException::class);
        $container->getParameter('pheature_flags_prefix');

        $expectedLoadedResources = 0;
        $loadedResources = $container->getResources();
        self::assertCount($expectedLoadedResources, $loadedResources);
    }

    public function testItShouldLoadApiServicesInContainer(): void
    {
        $expectedPrefix = 'a-prefix';

        $config = PheatureFlagsConfig::createDefault()
            ->withApiEnabled(true)
            ->withApiPrefix($expectedPrefix)
            ->build();

        $compilerPass = new ToggleAPIPass();
        $container = TestContainerFactory::create($compilerPass, null, $config);

        self::assertSame($expectedPrefix, $container->getParameter('pheature_flags_prefix'));

        $loadedResources = $container->getResources();

        self::assertCount(2, $loadedResources);

        $this->assertSimilarResourceIn('src/Resources/config/toggle_api/services/controller_services.yaml',
            $loadedResources);
        $this->assertSimilarResourceIn('src/Resources/config/toggle_api/services/handler_services.yaml',
            $loadedResources);
    }

    private function assertSimilarResourceIn(string $similarResource, array $currentResources): void
    {
        /** @var FileResource $currentResource */
        foreach ($currentResources as $currentResource) {
            if (false !== strpos($currentResource->getResource(), $similarResource)) {
                return;
            }
        }

        $actualResources = json_encode(
            array_map(static fn(FileResource $fileResource) => $fileResource->getResource(), $currentResources)
        );

        self::fail(
            sprintf(
                'Similar resource "%s" not found in current resources: [%s]',
                $similarResource,
                $actualResources
            )
        );
    }
}
