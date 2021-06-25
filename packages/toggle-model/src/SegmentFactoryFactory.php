<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Model\Toggle\SegmentFactory;
use Psr\Container\ContainerInterface;
use Pheature\Core\Toggle\Read\SegmentFactory as ReadSegmentFactory;

class SegmentFactoryFactory
{
    public function __invoke(ContainerInterface $container): ReadSegmentFactory
    {
        return new SegmentFactory();
    }
}
