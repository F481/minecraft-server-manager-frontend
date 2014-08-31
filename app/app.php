<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;
$app['root_dir'] = __DIR__.'/../';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $app['root_dir'].'views',
));


$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig');
});

return $app;