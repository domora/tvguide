<?php

namespace Domora\TvGuide;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry;
use Igorw\Silex\ConfigServiceProvider;
use DDesrosiers\SilexAnnotations\AnnotationServiceProvider;
use Doctrine\Common\Cache\FilesystemCache;
use Goutte\Client;

use Domora\Silex\Provider\DoctrineORMServiceProvider;
use Domora\TvGuide\Service\Serializer;
use Domora\TvGuide\Data\DataManager;
use Domora\TvGuide\Service\Wikipedia;
use Domora\TvGuide\Service\EntityProvider;
use Domora\TvGuide\Service\DateTimeSerializer;

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
        
        // Convert errors to exceptions
        ErrorHandler::register();
        
        $this['cache.directory'] = __DIR__.'/../../../app/cache';
        $this['vendor.directory'] = __DIR__.'/../../../vendor';

        $this->registerControllers();
        $this->registerServiceProviders();
        $this->registerInternalServices();
    }
    
    public function loadRoutes()
    {
        $this->error(function(\Exception $e, $code) {
            return $this['api.serializer']->serialize($e);
        });
        
        $this['dispatcher']->addListener('kernel.view', function(GetResponseForControllerResultEvent $event) {
            $request = $event->getRequest();
            $data = $event->getControllerResult();
            $contentType = $request->headers->get('Accept');
            
            if ($contentType == 'application/xml') {
                $format = 'xml';
            } else {
                $format = 'json';
            }
            
            if (is_array($data)) {
                if (is_string($data[1])) {
                    $data[1] = [$data[1]];
                }
                $response = $this['api.serializer']->serialize($data[0], $data[1], 200, $format);
            } else {
                $response = $this['api.serializer']->serialize($data, null, 200, $format);
            }
            
            $event->setResponse($response);
        });
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
        
        $this->register(new AnnotationServiceProvider(), [
            "annot.cache" => new FilesystemCache($this['cache.directory'] . '/silex-annotation'),
            "annot.controllers" => $this['tvguide.controllers']
        ]);

        $this['serializer'] = $this->share(function() {
            return SerializerBuilder::create()
                ->setDebug($this['debug'])
                ->setCacheDir($this['cache.directory'] . '/jms-serializer')
                ->addDefaultHandlers()
                ->configureHandlers(function(HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new DateTimeSerializer());
                })
                ->build();
        });
    }

    private function registerInternalServices()
    {
        ImageContentTrait::$imagesDirectory = $this['parameters']['images']['directory'];
        ImageContentTrait::$imagesBaseUri = $this['parameters']['images']['baseUri'];
            
        // Custom serializer relying on JMS Serializer
        $this['api.serializer'] = $this->share(function() {
            return new Serializer($this['serializer'], $this['debug']);
        });
        
        $this['entity.provider'] = $this->share(function() {
            return new EntityProvider($this['orm.em']); 
        });
        
        $this['wikipedia'] = $this->share(function() {
            return new Wikipedia();
        });
    }
    
    private function registerControllers()
    {
        $this['tvguide.controllers'] = [
            "v1" => [
                "Domora\TvGuide\Controller\ChannelController",
                "Domora\TvGuide\Controller\ProgramController",
                "Domora\TvGuide\Controller\ServiceController"
            ]
        ];        
    }
}
