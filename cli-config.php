<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Domora\TvGuide\Application();

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($app['orm.em']);
