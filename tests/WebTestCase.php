<?php

namespace Domora\Tests;

abstract class WebTestCase extends \Silex\WebTestCase
{
    public function createApplication()
    {
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            __DIR__.'/../vendor/jms/serializer/src'
        );
        
        $app = new \Domora\TvGuide\Application();
        $app['debug'] = true;
        $app['exception_handler']->disable();
        $app->loadRoutes();
        
        umask(0000);
        
        return $app;
    }
}