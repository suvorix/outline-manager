<?php
namespace App\Core;

class Router
{
    private $routes = array();
    private $currentGroup = '';
    private $notFoundCallback;

    public function add($method, $path, $callback)
    {
        $path = $this->currentGroup . $path;
        
        array_push($this->routes, array(
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ));
    }

    public function get($path, $callback)
    {
        $this->add('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->add('POST', $path, $callback);
    }

    public function put($path, $callback)
    {
        $this->add('PUT', $path, $callback);
    }

    public function delete($path, $callback)
    {
        $this->add('DELETE', $path, $callback);
    }

    public function patch($path, $callback)
    {
        $this->add('PATCH', $path, $callback);
    }

    public function group($prefix, $callback)
    {
        $previousGroup = $this->currentGroup;

        $this->currentGroup = $previousGroup . $prefix;

        $callback($this);

        $this->currentGroup = $previousGroup;
    }

    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }

    private function parseCallback($callback)
    {
        if (is_callable($callback)) {
            return $callback;
        }

        if (is_string($callback)) {
            // Формат: Controller@method
            if (strpos($callback, '@') !== false) {
                list($controller, $method) = explode('@', $callback);
                $controllerClass = "App\\Controllers\\" . $controller;
                
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    if(method_exists($controllerInstance, $method)) {
                        return array($controllerInstance, $method);
                    }
                }
            }
        }

        return null;
    }

    private function matchRoute($method, $path)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['path']);
            if (preg_match($pattern, $path, $matches)) {
                // Извлекаем параметры
                $params = array();
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                return array(
                    'callback' => $route['callback'],
                    'params' => $params
                );
            }
        }

        return null;
    }

    private function convertToRegex($path)
    {
        // Заменяем параметры вида {param} на регулярные выражения
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        
        return $pattern;
    }

    public function dispatch($requestUri = null, $requestMethod = null)
    {
        // Получаем путь и метод запроса
        $path = parse_url($requestUri !== null ? $requestUri : $_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $requestMethod !== null ? $requestMethod : $_SERVER['REQUEST_METHOD'];
        
        // Убираем базовый путь если нужно
        $basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/') {
            $path = str_replace($basePath, '', $path);
        }

        // Ищем подходящий маршрут
        $route = $this->matchRoute($method, $path);

        if ($route) {
            // Парсим callback
            $callback = $this->parseCallback($route['callback']);
            
            if ($callback) {
                // Вызываем обработчик с параметрами
                $this->invokeCallback($callback, $route['params']);
                return;
            }
        }

        // Маршрут не найден
        $this->handleNotFound();
    }

    private function invokeCallback($callback, $params)
    {
        // Просто передаем все параметры как массив
        // Контроллеры сами разберутся какие параметры им нужны
        if (is_array($callback)) {
            // Для вызова метода контроллера: [object, method]
            $object = $callback[0];
            $method = $callback[1];
            $object->$method($params);
        } else {
            // Для обычной функции
            $callback($params);
        }
    }

    private function handleNotFound()
    {
        if ($this->notFoundCallback) {
            ($this->notFoundCallback)();
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Route not found',
                'method' => $_SERVER['REQUEST_METHOD'],
                'path' => $_SERVER['REQUEST_URI']
            ]);
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}