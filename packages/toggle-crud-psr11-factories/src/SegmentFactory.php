<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Model\Toggle\SegmentFactory as ModelSegmentFactory;
use Psr\Container\ContainerInterface;
use Pheature\Core\Toggle\Read\SegmentFactory as ReadSegmentFactory;

class SegmentFactory
{
    public function __invoke(ContainerInterface $container): ReadSegmentFactory
    {
        return new ModelSegmentFactory();
    }
}
