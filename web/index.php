<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'JMS\Serializer\Annotation',
    __DIR__.'/../vendor/jms/serializer/src'
);

$app = new Domora\TvGuide\Application();

umask(0000);

$app->loadRoutes();
$app->run();
