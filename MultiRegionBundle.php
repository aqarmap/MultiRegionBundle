<?php

namespace Aqarmap\Bundle\MultiRegionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Aqarmap\Bundle\MultiRegionBundle\DependencyInjection\Compiler\OverrideRoutingCompilerPass;
use Aqarmap\Bundle\MultiRegionBundle\DependencyInjection\MultiRegionExtension;

use Symfony\Component\DependencyInjection\Compiler\ResolveDefinitionTemplatesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MultiRegionBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OverrideRoutingCompilerPass());
    }

    public function getContainerExtension()
    {
        return new MultiRegionExtension();
    }
}
