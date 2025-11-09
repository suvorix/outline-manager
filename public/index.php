<?php
require_once '../app/bootstrap.php';

use App\Core\Router;

$router = new Router();

$router->add('GET', '/login', 'PageController@login');
// // $router->add('POST', '/login', 'AuthController@login');
// // $router->add('POST', '/logout', 'AuthController@logout');
$router->add('GET', '/', 'PageController@dashboard');
$router->add('GET', '/servers', 'PageController@servers');
$router->add('GET', '/add-server', 'PageController@addServer');

// // // API маршруты (для внутреннего использования)
// // $router->add('POST', '/api/users/create', 'AdminController@createUser');
// // $router->add('POST', '/api/users/update', 'AdminController@updateUser');
$router->dispatch($_SERVER['REQUEST_URI']);