<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Domora\TvGuide\Application();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($app['orm.em']);
