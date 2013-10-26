<?php

namespace Aqarmap\Bundle\MultiRegionBundle\Router;

use Symfony\Component\Routing\RouteCollection;

class MultiRegionLoader
{
    private $regions;

    public function __construct(array $regions)
    {
        $this->regions = $regions;
    }

    public function load(RouteCollection $collection)
    {
        $multiRegionCollection = new RouteCollection();

        foreach ($collection->getResources() as $resource) {
            $multiRegionCollection->addResource($resource);
        }

        foreach ($collection->all() as $name => $route) {
            // Exclude Routes
            if( substr($name, 0, 1) === '_' || $route->getOption('regional') === FALSE ) {
                $multiRegionCollection->add($name, $route);
                continue;
            }

            foreach( $this->regions as $region => $config )
            {
                $newRoute = clone $route;
                $pattern = strtr($config['url_pattern'], array(
                    '{_pattern}'    => $newRoute->getPattern(),
                    '{_region}'     => $region
                ));
                $newRoute->setPattern($pattern);

                $newRoute->setRequirement('_locale', implode('|', $config['enabled_locales']));
                $newRoute->setDefault('_region', $region);
                $multiRegionCollection->add(self::getRegionPrefix($region) . $name, $newRoute);
            }
        }

        return $multiRegionCollection;
    }

    static function getRegionPrefix($regionName)
    {
        return '__'. mb_strtoupper($regionName) .'_';
    }
}
