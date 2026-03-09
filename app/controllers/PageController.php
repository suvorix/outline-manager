<?php
namespace App\Controllers;

use App\Controllers\AuthController;
use App\Controllers\ServerApiController;
use App\Models\Server;
use App\Models\ServerKey;
use App\Models\KeyStatistic;

class PageController
{
    private $pageInfo = array(
        'current_page' => '',
        'title' => 'Outline Manager',
        'description' => '',
    );

    public function __construct() {}

    private function view($page, $template = 'main') 
    {
        $this->pageInfo['current_page'] = $page;
        $APP['content'] = '';
        $APP['info'] = $this->pageInfo;
        ob_start();
        require_once BASE_PATH . '/views' . $page . '.php';
        $APP['content'] = ob_get_clean();
        require_once BASE_PATH . '/views/templates/' . $template . '.php';
    }

    private function redirect($url) 
    {
        header('Location: ' . $url); 
        exit();
    }

    private function checkAuth() 
    {
        $authController = new AuthController();
        return $authController->checkAuth();
    }
    
    public function login( $params )
    {
        if( $this->checkAuth() ) { $this->redirect('/'); }
        $this->pageInfo['title'] = 'Авторизация | ' . $this->pageInfo['title'];
        $this->view('/login', 'empty');
    }
    
    public function dashboard( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        
        $serverModel = new Server();

        $this->pageInfo['server_counts'] = $serverModel->count();
        
        $serverKeyModel = new ServerKey();

        $this->pageInfo['key_counts'] = $serverKeyModel->count();

        $keyStatisticModel = new KeyStatistic();

        $this->pageInfo['key_stat'] = $keyStatisticModel->getStatistictLast5min();

        $this->pageInfo['title'] = 'Панель управления | ' . $this->pageInfo['title'];
        $this->view('/dashboard');
    }
    
    public function server_list( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Сервера | ' . $this->pageInfo['title'];
    
        $serverModel = new Server();
        $this->pageInfo['servers'] = $serverModel->getServers();
        
        $this->view('/server/list');
    }
    
    public function server_add( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Добавление сервера | ' . $this->pageInfo['title'];
        $this->view('/server/add');
    }
    
    public function server_add_form( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        if( !isset($_POST['server-data']) ) { $this->redirect('/server/add?error='.urldecode('Данные не переданы')); }

        $serverInfo = json_decode($_POST['server-data'], true);
        if($serverInfo === null) { $this->redirect('/server/add?error='.urldecode('Данные должны быть в формате JSON')); }
        if( !isset($serverInfo['apiUrl']) ) { $this->redirect('/server/add?error='.urldecode('Не передан "apiUrl"')); }
        if( !isset($serverInfo['certSha256']) ) { $this->redirect('/server/add?error='.urldecode('Не передан "certSha256"')); }
        
        $serverName = 'Outline сервер';
        if( isset($_POST['server-name']) ) {
            if( trim($_POST['server-name']) != '' ) {
                $serverName = trim($_POST['server-name']);
            }
        }

        $serverKeyLimit = -1;
        if( isset($_POST['server-key-limit']) ) {
            if( trim($_POST['server-key-limit']) != '' ) {
                $serverKeyLimit = trim($_POST['server-key-limit']);
            }
        }

        $serverApiController = new ServerApiController($serverInfo['apiUrl'], $serverInfo['certSha256']);
        if($serverApiController->server() === NULL) { $this->redirect('/server/add?error='.urldecode('Нет связи с сервером')); }

        $serverApiController->changeServer(array('name' => $serverName));

        $serverModel = new Server();
        $serverModel->add($serverName, $serverInfo['apiUrl'], $serverInfo['certSha256'], $serverKeyLimit);
        $this->redirect('/server/list?success='.urldecode('Сервер добавлен'));
    }
    
    public function server_edit( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Редактирование сервера | ' . $this->pageInfo['title'];

        $server_id = $params['server_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/server/list?error='.urldecode('Передан неправильный идентификатор сервера')); }

        $serverModel = new Server();
        $server = $serverModel->getServer($server_id);

        if($server === false) {
            $this->redirect('/server/list?error='.urldecode('Сервер не найден'));
        }

        $this->pageInfo['server'] = $server;

        $this->view('/server/edit');
    }
    
    public function server_edit_form( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }

        $id = $_POST['server-id'];
        $name = $_POST['server-name'];
        $keyLimit = $_POST['server-key-limit'];

        if (filter_var($id, FILTER_VALIDATE_INT) === false) { $this->redirect('/server/edit/' . $id . '?error='.urldecode('Передан неправильный идентификатор сервера')); }

        $serverModel = new Server();
        $serverModel->edit($id, array(
            'name' => $name,
            'key_limit' => $keyLimit
        ));

        $this->redirect('/server/list?success='.urldecode('Сервер изменён'));
    }
    
    public function server_del( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }

        $server_id = $params['server_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/server/list?error='.urldecode('Передан неправильный идентификатор сервера')); }

        $serverModel = new Server();
        $serverModel->del($server_id);

        $this->redirect('/server/list?success='.urldecode('Сервер удалён'));
    }
    
    public function key_list( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Список ключей | ' . $this->pageInfo['title'];

        $server_id = $params['server_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/?error='.urldecode('Передан неправильный идентификатор сервера')); }

        $serverModel = new Server();
        $server = $serverModel->getServer($server_id);

        if($server === false) {
            $this->redirect('/server/list?error='.urldecode('Сервер не найден'));
        }

        $this->pageInfo['server'] = $server;

        $serverKeyModel = new ServerKey();
        $this->pageInfo['keys'] = $serverKeyModel->getKeys($server['id']);

        $this->view('/server/key/list');
    }
    
    public function key_add( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Добавление ключа | ' . $this->pageInfo['title'];

        $server_id = $params['server_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/?error='.urldecode('Передан неправильный идентификатор сервера')); }

        $serverModel = new Server();
        $server = $serverModel->getServer($server_id);

        if($server === false) {
            $this->redirect('/server/list?error='.urldecode('Сервер не найден'));
        }

        $this->pageInfo['server'] = $server;

        $serverApiController = new ServerApiController($server['apiUrl'], $server['certSha256']);

        $this->pageInfo['encryptMethods'] = $serverApiController->listMethods;

        $this->view('/server/key/add');
    }
    
    public function key_add_form( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }

        $server_id = $params['server_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/?error='.urldecode('Передан неправильный идентификатор сервера')); }

        // Получаем данные сервера
        $serverModel = new Server();
        $server = $serverModel->getServer($server_id);

        if($server === false) {
            $this->redirect('/server/list?error='.urldecode('Сервер не найден'));
        }

        $keyName = $_POST['key-name'];
        $keyPassword = $_POST['key-password'];
        $keyPort = (int) $_POST['key-port'];
        $keyMethod = $_POST['key-method'];
        $keyDateEnd = $_POST['key-date-end'];

        $keyInformation = array();

        if($keyName != '') { $keyInformation['name'] = $keyName; }
        if($keyPassword != '') { $keyInformation['password'] = $keyPassword; }
        if($keyPort != 0) { $keyInformation['port'] = $keyPort; }
        if($keyMethod != '') { $keyInformation['method'] = $keyMethod; }
        
        // Создаём ключ
        $serverApiController = new ServerApiController($server['apiUrl'], $server['certSha256']);
        $keyResponse = json_decode($serverApiController->createAccessKey($keyInformation), true);
        
        // Выводим уведомление об ошибке создания ключа
        if($keyResponse === null) {
            $this->redirect("/server/{$server['id']}/key/add?error=".urldecode('Произошла ошибка при создании ключа'));
        }

        $addKeyInfo = array(
            'server_id' => $server['id']
        );
        if($keyDateEnd != '') { $addKeyInfo['date_end'] = $keyDateEnd; }

        // Сохраняем ключ в БД 
        $serverKeyModel = new ServerKey();
        $serverKeyModel->add($keyResponse, $addKeyInfo);

        $this->redirect("/server/{$server['id']}/key/list?success=".urldecode('Ключ создан'));
    }

    public function key_edit( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Редактирование ключа | ' . $this->pageInfo['title'];

        $server_id = $params['server_id'];
        $key_id = $params['key_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/server/list?error='.urldecode('Передан неправильный идентификатор сервера')); }
        if (filter_var($key_id, FILTER_VALIDATE_INT) === false) { $this->redirect("/server/{$server_id}/key/list?error=".urldecode('Передан неправильный идентификатор ключа')); }

        $serverModel = new Server();
        $server = $serverModel->getServer($server_id);

        if($server === false) {
            $this->redirect('/server/list?error='.urldecode('Сервер не найден'));
        }

        $this->pageInfo['server'] = $server;

        $serverKeyModel = new ServerKey();
        $key = $serverKeyModel->getKey($key_id);

        if($key === false) {
            $this->redirect("/server/{$server['id']}/key/list?error=".urldecode('Ключ не найден'));
        }

        $this->pageInfo['key'] = $key;

        $serverApiController = new ServerApiController($server['apiUrl'], $server['certSha256']);

        $this->pageInfo['encryptMethods'] = $serverApiController->listMethods;

        $this->view('/server/key/edit');
    }
    
    public function key_edit_form( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }

        $server_id = $params['server_id'];
        $key_id = $params['key_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/server/list?error='.urldecode('Передан неправильный идентификатор сервера')); }
        if (filter_var($key_id, FILTER_VALIDATE_INT) === false) { $this->redirect("/server/{$server_id}/key/list?error=".urldecode('Передан неправильный идентификатор ключа')); }

        $serverModel = new Server();
        $server = $serverModel->getServer($server_id);

        if($server === false) {
            $this->redirect('/server/list?error='.urldecode('Сервер не найден'));
        }
        
        $serverKeyModel = new ServerKey();
        $key = $serverKeyModel->getKey($key_id);

        if($key === false) {
            $this->redirect("/server/{$server['id']}/key/list?error=".urldecode('Ключ не найден'));
        }

        $keyName = $_POST['key-name'];
        $keyDateEnd = $_POST['key-date-end'];

        // Редактируем ключ
        $serverApiController = new ServerApiController($server['apiUrl'], $server['certSha256']);
        $keyResponse = $serverApiController->changeAccessKey($key['key_id'], array('name' => $keyName));

        // Выводим уведомление об ошибке редактирования ключа
        if($keyResponse === false) {
            $this->redirect("/server/{$server['id']}/key/edit/{$key['id']}?error=".urldecode('Произошла ошибка при редактировании ключа'));
        }

        $updateInfo = array(
            'key_name' => $keyName,
            'date_end' => null,
        );

        if($keyDateEnd != '') { $updateInfo['date_end'] = $keyDateEnd; }

        // Сохраняем ключ в БД 
        $serverKeyModel = new ServerKey();
        $serverKeyModel->edit($key['id'], $updateInfo);

        $this->redirect("/server/{$server['id']}/key/list?success=".urldecode('Ключ изменён'));
    }
    
    public function key_del( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }

        $server_id = $params['server_id'];
        $key_id = $params['key_id'];

        if (filter_var($server_id, FILTER_VALIDATE_INT) === false) { $this->redirect('/server/list?error='.urldecode('Передан неправильный идентификатор сервера')); }
        if (filter_var($key_id, FILTER_VALIDATE_INT) === false) { $this->redirect("/server/{$server_id}/key/list?error=".urldecode('Передан неправильный идентификатор ключа')); }

        $serverModel = new Server();
        $server = $serverModel->getServer($server_id);

        if($server === false) {
            $this->redirect('/server/list?error='.urldecode('Сервер не найден'));
        }

        $serverKeyModel = new ServerKey();
        $key = $serverKeyModel->getKey($key_id);

        if($key === false) {
            $this->redirect("/server/{$server['id']}/key/list?error=".urldecode('Ключ не найден'));
        }

        $serverKeyModel->del($key_id);

        $this->redirect("/server/{$server['id']}/key/list?success=".urldecode('Ключ удалён'));
    }
}