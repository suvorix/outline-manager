<?php
// Автозагрузка классов
spl_autoload_register(function ($class) {
    $AppDir = str_replace('\\', '/', __DIR__);
    $class = str_replace('\\', '/', $class);
    $class = '/' . str_replace('App/', '', $class);
    foreach(array('/Core/', '/Controllers/', '/Models/') as $item) {
        $class = str_replace($item, strtolower($item), $class);
    }
    $file = __DIR__ . $class . '.php'; 
    if (file_exists($file)) {
        require_once $file;
    }
});

// Загрузка конфигурации
require_once 'config.php';

// Инициализация сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}