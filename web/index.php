<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once 'data.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/schedule', function () use ($app, $guide) {
    return $app->json($guide);
});

$app->get('/programs/{id}', function($id) use ($app,$programs) {
    return $app->json($programs[$id]);
});

$app->run();
