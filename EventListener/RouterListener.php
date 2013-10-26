<?php

namespace Aqarmap\Bundle\MultiRegionBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RouterListener implements EventSubscriberInterface
{
    private $regions;
    private $defaultRegion;

    public function __construct(array $regions, $defaultRegion)
    {
        $this->regions = $regions;
        $this->defaultRegion = $defaultRegion;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ('' !== rtrim($request->getPathInfo(), '/')) {
            return;
        }

        $region = $this->regions[$this->defaultRegion];

        $pathinfo = strtr($region['url_pattern'], array(
            '{_pattern}'    => '/',
            '{_region}'     => $this->defaultRegion,
            '{_locale}'     => $region['default_locale']
        ));

        $params = $request->query->all();

        $event->setResponse(new RedirectResponse( $request->getBaseUrl(). $pathinfo . ($params ? '?'. http_build_query($params) : '') ));
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}
