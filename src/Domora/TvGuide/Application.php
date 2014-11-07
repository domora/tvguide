<?php

namespace Domora\TvGuide;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
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

use Domora\TvGuide\Service\ImageContentTrait;
use Domora\TvGuide\Response\Error;
use Domora\TvGuide\Controller\ChannelController;
use Domora\TvGuide\Controller\ProgramController;
use Domora\TvGuide\Controller\ServiceController;

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
        $this->registerControllers();
    }
    
    public function loadRoutes()
    {
        $channelProvider = $this['entity.provider']->getProvider(ChannelController::CHANNEL_ENTITY);
        $programProvider = $this['entity.provider']->getProvider(ProgramController::PROGRAM_ENTITY);
        $serviceProvider = $this['entity.provider']->getProvider(ServiceController::SERVICE_ENTITY);

        $api = $this['controllers_factory'];

        $api->get('/channels', 'controller.channel:getChannelsAction');
        $api->get('/channels/{channel}', 'controller.channel:getChannelAction')->convert('channel', $channelProvider);
        $api->post('/channels/{channel}/programs', 'controller.channel:postChannelProgramsAction')->convert('channel', $channelProvider);

        $api->get('/programs', 'controller.program:getProgramsAction');
        $api->get('/programs/{program}', 'controller.program:getProgramAction')->convert('program', $programProvider);
        $api->delete('/programs/{program}', 'controller.program:deleteProgramAction')->convert('program', $programProvider);
        
        $api->get('/services', 'controller.service:getServicesAction');
        $api->get('/services/{service}', 'controller.service:getServiceAction')->convert('service', $serviceProvider);
        
        $this->error(function(Error $e, $code) {
            return $this['api.serializer']->serialize($e);
        });
        
        $this->mount('/v1', $api);
    }

    private function registerServiceProviders()
    {
        $root = __DIR__ . "/../../..";
        $configurationVars = [
            'root' => $root,
            'uri' => isset($_SERVER['REQUEST_SCHEME']) ? 
                sprintf('%s://%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], dirname($_SERVER['SCRIPT_NAME'])) :
                ''
        ];
        
        // Load user global configuration
        $this->register(new ConfigServiceProvider("$root/app/config/config.json", $configurationVars));
        
        // Load environment configuration
        $env = $this['environment'] ?: 'prod';
        $this->register(new ConfigServiceProvider("$root/app/config/$env.json", $configurationVars));
        
        $this->register(new ServiceControllerServiceProvider());

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
        ImageContentTrait::$imagesDirectory = $this['parameters']['images']['directory'];
        ImageContentTrait::$imagesBaseUri = $this['parameters']['images']['baseUri'];
            
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
    
    private function registerControllers()
    {
        $this['controller.channel'] = $this->share(function() {
            return new Controller\ChannelController($this['orm.em'], $this['api.serializer']); 
        });
        
        $this['controller.program'] = $this->share(function() {
            return new Controller\ProgramController($this['orm.em'], $this['api.serializer']); 
        });
        
        $this['controller.service'] = $this->share(function() {
            return new Controller\ServiceController($this['orm.em'], $this['api.serializer']); 
        });
    }
}
