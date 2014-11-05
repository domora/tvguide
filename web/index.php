<?php

use Domora\TvGuide\Controller\ChannelController;
use Domora\TvGuide\Controller\ProgramController;

$loader = require_once __DIR__.'/../vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app = new Domora\TvGuide\Application();

umask(0000);

$channelProvider = $app['entity.provider']->getProvider(ChannelController::CHANNEL_ENTITY);
$programProvider = $app['entity.provider']->getProvider(ProgramController::PROGRAM_ENTITY);

$api = $app['controllers_factory'];

$api->get('/channels', 'controller.channel:getChannelsAction');
$api->get('/channels/{channel}', 'controller.channel:getChannelAction')->convert('channel', $channelProvider);

$api->get('/programs', 'controller.program:getProgramsAction');
$api->get('/programs/{program}', 'controller.program:getProgramAction')->convert('program', $programProvider);

//$app->mount('/v1', new Domora\TvGuide\Controller\ChannelController());
//$app->mount('/v1', new Domora\TvGuide\Controller\ControllerProvider());
$app->mount('/v1', $api);
$app->run();
