<?php

require_once __DIR__.'/../vendor/autoload.php';

use F481\MSM_Frontend\MSMCommander;
use Symfony\Component\HttpFoundation\Request;

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

$app->get('/msm/{func}', function($func, Request $request) use ($app, $commander) {
    $name = $request->get('name');

    if ($func != null) {
        switch ($func) {
            case 'create':
                if ($name != null) {
                    $response = $commander->createServer($name);
                }
                break;
        }
    }

    return $response;
})->value('func', null)->value('name', null);


$app['overview'] = function() use ($commander) {
    $servers = array();

    foreach ($commander->getServers() as $serverName) {
        $tempObj = new stdClass();
        $tempObj->name = $serverName;
        $tempObj->isRunning = $commander->isRunning($serverName);
        $tempObj->players = $commander->getPlayers($serverName);
        array_push($servers, $tempObj);
    }

    //var_dump($servers);
    return $servers;
};

return $app;