<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app = new Domora\TvGuide\Application();

umask(0000);

$app->mount('/v1', new Domora\TvGuide\Controller\ControllerProvider());
$app->run();
