<?php

namespace Domora\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;
use Igorw\Silex\ConfigServiceProvider;

use Domora\Tests\DataFixtures;

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
    
    public function setUp()
    {
        parent::setUp();
        $this->loadFixtures();
    }
    
    protected function loadFixtures()
    {
        $loader = new Loader();
        $loader->addFixture(new DataFixtures);
        
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->app['orm.em'], $purger);
        $executor->execute($loader->getFixtures());
    }
    
    protected function createAuthenticatedClient()
    {
        return $this->createClient(['PHP_AUTH_USER' => 'user_test', 'PHP_AUTH_PW' => 'foo']);
    }
}