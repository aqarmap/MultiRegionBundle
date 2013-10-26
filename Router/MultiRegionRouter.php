<?php

namespace Aqarmap\Bundle\MultiRegionBundle\Router;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class MultiRegionRouter extends Router
{
    private $loaderServiceId;
    private $defaultRegion;
    private $regions;

    public function __construct(ContainerInterface $container, $resource, array $options = array(), RequestContext $context = null)
    {
        $this->container = $container;
        return parent::__construct($container, $resource, $options, $context);
    }

    public function setLoaderServiceId($className)
    {
        $this->loaderServiceId = $className;
    }

    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

    public function setDefaultRegion($region)
    {
        $this->defaultRegion = $region;
    }

    public function getRouteCollection()
    {
        $collection = parent::getRouteCollection();
        return $this->container->get($this->loaderServiceId)->load($collection);
    }

    public function match($pathinfo)
    {
        return $this->getMatcher()->match($pathinfo);
    }

    public function generate($name, $parameters = array(), $absolute = FALSE)
    {

        $region = $this->defaultRegion;

        if( ! empty($parameters['_region']) ) {
            $region = $parameters['_region'];
        }

        $generator = $this->getGenerator();

        try {
            return $generator->generate(MultiRegionLoader::getRegionPrefix($region) . $name, $parameters, $absolute);
        } catch ( RouteNotFoundException $ex ) {

        }

        return $generator->generate($name, $parameters, $absolute);
    }
}
