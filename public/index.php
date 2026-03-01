<?php
require_once '../app/bootstrap.php';

use App\Core\Router;

$router = new Router();

// Авторизация
$router->add('GET', '/login', 'PageController@login');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/logout', 'AuthController@logout');

// Навигация
$router->add('GET', '/', 'PageController@dashboard');
$router->group('/server', function($router){
    $router->add('GET', '/list', 'PageController@server_list');
    $router->add('GET', '/add', 'PageController@server_add');
    $router->add('POST', '/add', 'PageController@server_add_form');
    $router->add('GET', '/edit/{server_id}', 'PageController@server_edit');
    $router->add('POST', '/edit', 'PageController@server_edit_form');
    $router->add('GET', '/del/{server_id}', 'PageController@server_del');

    $router->group('/{server_id}/key', function($router){
        $router->add('GET', '/list', 'PageController@key_list');
        $router->add('GET', '/add', 'PageController@key_add');
        $router->add('POST', '/add', 'PageController@key_add_form');
        $router->add('GET', '/edit/{key_id}', 'PageController@key_edit');
        $router->add('POST', '/edit', 'PageController@key_edit_form');
        $router->add('GET', '/del/{key_id}', 'PageController@key_del');
    });
});

// Крон задачи
$router->group('/cron', function($router){
    $router->add('GET', '/check-servers', 'CronController@check_servers');
});

$router->dispatch($_SERVER['REQUEST_URI']);