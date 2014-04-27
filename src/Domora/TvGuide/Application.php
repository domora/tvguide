<?php

namespace Domora\TvGuide;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Macedigital\Silex\Provider\SerializerProvider;

use Domora\TvGuide\Service\Serializer;
use Domora\TvGuide\Data\DataManager;

class Application extends SilexApplication
{
    public function __construct()
    {
        parent::__construct();
        
        $this['debug'] = true;
        $this['cache.directory'] = __DIR__.'/../../../app/cache';
        $this['vendor.directory'] = __DIR__.'/../../../vendor';

        $this->registerServiceProviders();
        $this->registerInternalServices();
    }

    private function registerServiceProviders()
    {
        $this->register(new DoctrineServiceProvider(), [
            'db.options' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'dbname' => 'tvguide',
                'user' => 'root',
                'password' => '',
            ],
        ]);

        $this->register(new DoctrineOrmServiceProvider(), array(
            "orm.proxies_dir" => $this['cache.directory'],
            "orm.em.options" => array(
                "mappings" => array(
                    array(
                        "type" => "annotation",
                        "use_simple_annotation_reader" => false,
                        "namespace" => "Domora\TvGuide\Data",
                        "path" => __DIR__."/Data",
                    ),
                ),
            ),
        ));

        $this['serializer.cache_dir'] = $this['cache.directory'];
        $this->register(new SerializerProvider());
    }

    private function registerInternalServices()
    {
        // Custom serializer relying on JMS Serializer
        $this['api.serializer'] = $this->share(function() {
            return new Serializer($this['serializer']);
        });
    }
}
