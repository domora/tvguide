<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';
require_once 'data.php';

use Domora\TvGuide\Data\Program;
use Domora\TvGuide\Data\Channel;

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app = new Domora\TvGuide\Application();

$app->mount('/v1', new Domora\TvGuide\Controller\ControllerProvider());
$app->run();
