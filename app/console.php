<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use Domora\TvGuide\Command\UpdateDatabaseCommand;
use Domora\TvGuide\Command\RunTasksCommand;
use Domora\TvGuide\Command\ParseCommand;

use Symfony\Component\Console\Application as ConsoleApplication;

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$silex = new Domora\TvGuide\Application();

umask(0000);

$console = new ConsoleApplication();
$console->add(new UpdateDatabaseCommand($silex));
$console->add(new RunTasksCommand($silex));
$console->add(new ParseCommand($silex));
$console->run();
