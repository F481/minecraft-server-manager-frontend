<?php

require_once __DIR__.'/../vendor/autoload.php';

use F481\MSM_Frontend\MSMCommander;

$app = new Silex\Application();
$commander = new MSMCommander();

$app['debug'] = true;
$app['root_dir'] = __DIR__.'/../';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $app['root_dir'].'views',
));


$app->get('/', function() use ($app, $commander) {
    $servers = $app['overview'];

    return $app['twig']->render('index.twig', array(
       'servers' => $servers,
    ));
});


$app['overview'] = function() use ($commander) {
    $servers = array();
    $serverNames = $commander->listServer();

    foreach ($serverNames as $serverName) {
        $tempObj = new stdClass();
        $tempObj->name = $serverName;
        $tempObj->isRunning = $commander->isRunning($serverName);
        // TODO players
        array_push($servers, $tempObj);
    }

    //var_dump($servers);
    return $servers;
};

return $app;