<?php

namespace Domora\TvGuide;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry;
use Igorw\Silex\ConfigServiceProvider;
use Goutte\Client;

use Domora\Silex\Provider\DoctrineORMServiceProvider;
use Domora\TvGuide\Service\Serializer;
use Domora\TvGuide\Data\DataManager;
use Domora\TvGuide\Service\Wikipedia;
use Domora\TvGuide\Service\DateTimeSerializer;
use Domora\TvGuide\Service\EntityProviderFactory;
use Domora\TvGuide\Provider\FranceTelevision;

class Application extends SilexApplication
{
    public function __construct()
    {
        parent::__construct();
        
        $this['cache.directory'] = __DIR__.'/../../../app/cache';
        $this['vendor.directory'] = __DIR__.'/../../../vendor';
        $this['image.directory'] = __DIR__.'/../../../web/images/programs';

        $this->registerServiceProviders();
        $this->registerInternalServices();
    }

    private function registerServiceProviders()
    {
        $this->register(new ConfigServiceProvider(__DIR__."/../../../app/config/config.json"));
        
        $env = $this['environment'] ?: 'prod';
        $this->register(new ConfigServiceProvider(__DIR__."/../../../app/config/$env.json"));

        $this->register(new DoctrineServiceProvider(), [
            'db.options' => [
                'driver' => $this['parameters']['db']['driver'],
                'host' => $this['parameters']['db']['host'],
                'dbname' => $this['parameters']['db']['name'],
                'user' => $this['parameters']['db']['user'],
                'password' => $this['parameters']['db']['password'],
                'charset' => 'utf8'
            ],
        ]);

        $this->register(new DoctrineORMServiceProvider(), [
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
        ]);

        $this['serializer'] = $this->share(function() {
            $builder = SerializerBuilder::create();
            $builder->setDebug($this['debug']);
            $builder->setCacheDir($this['cache.directory']);
            $builder->configureHandlers(function(HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new DateTimeSerializer());
            });

            return $builder->build();
        });
    }

    private function registerInternalServices()
    {
        define("PROGRAMS_IMAGE_URI", $this['parameters']['uri']['programs']);
        define("CHANNELS_IMAGE_URI", $this['parameters']['uri']['channels']);
        
        // Custom serializer relying on JMS Serializer
        $this['api.serializer'] = $this->share(function() {
            return new Serializer($this['serializer']);
        });
        
        $this['entity.provider'] = $this->share(function() {
            return new EntityProviderFactory($this['orm.em']); 
        });
        
        // Custom goutte client
        $this['scraper.client'] = function() {
            $client = new Client();
            
            if (isset($this['parameters']['tvscraper']['proxy'])) {
                $client->getClient()->getConfig()->setPath('request.options/proxy', $this['parameters']['tvscraper']['proxy']);
            }
            
            $client->setHeader('User-Agent', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:28.0) Gecko/20100101 Firefox/29.0');
            
            return $client;
        };

        $this['wikipedia'] = $this->share(function() {
            return new Wikipedia();
        });
        
        $this['provider.francetv'] = $this->share(function() {
            return new FranceTelevision($this['scraper.client'], $this['orm.em']);
        });
    }
}
