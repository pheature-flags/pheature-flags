<?php

declare(strict_types=1);

namespace Pheature\Community\Mezzio;

use Mezzio\Application;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Psr\Container\ContainerInterface;

use function sprintf;

final class RouterDelegator
{
    public function __invoke(ContainerInterface $container, string $serviceName, callable $callback): Application
    {
        /**
 * @var $app Application
*/
        $app = $callback();

        $config = $container->get('config');
        $prefix = $config['pheature_flags']['route_prefix'];
        $path = sprintf('%s/features', $prefix);
        $pathWithId = sprintf('%s/{feature_id}', $path);

        $app->get($path, [GetFeatures::class], 'get_features');
        $app->get($pathWithId, [GetFeature::class], 'get_feature');
        $app->post($pathWithId, [PostFeature::class], 'post_feature');
        $app->patch($pathWithId, [BodyParamsMiddleware::class, PatchFeature::class], 'patch_feature');
        $app->delete($pathWithId, [DeleteFeature::class], 'delete_feature');

        return $app;
    }
}
