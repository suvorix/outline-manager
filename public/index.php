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
$router->add('GET', '/servers', 'PageController@servers');
$router->add('GET', '/add-server', 'PageController@addServer');
$router->add('POST', '/add-server', 'PageController@addServerForm');

$router->dispatch($_SERVER['REQUEST_URI']);