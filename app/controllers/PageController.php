<?php
namespace App\Controllers;

use App\Controllers\AuthController;
use App\Models\Server;

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

    private function redirect($url) {
        header('Location: ' . $url); 
        exit();
    }

    private function checkAuth() {
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
        
        $modelServer = new Server();

        $this->pageInfo['server_count'] = $modelServer->count();

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

        $serverModel = new Server();
        $serverModel->add($serverName, $serverInfo['apiUrl'], $serverInfo['certSha256'], $serverKeyLimit);
        $this->redirect('/server/list?success='.urldecode('Сервер добавлен'));
    }
    
    public function server_edit( $params )
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Редактирование сервера | ' . $this->pageInfo['title'];

        if (filter_var($params['id'], FILTER_VALIDATE_INT) === false) { $this->redirect('/server/list?error='.urldecode('Передан неправильный идентификатор сервера')); }

        
        $serverModel = new Server();
        $this->pageInfo['server'] = $serverModel->getServer($params['id']);
        if($this->pageInfo['server'] === false) { $this->redirect('/server/list?error='.urldecode('Сервер не найден')); }

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

        if (filter_var($params['id'], FILTER_VALIDATE_INT) === false) { $this->redirect('/server/list?error='.urldecode('Передан неправильный идентификатор сервера')); }

        $serverModel = new Server();
        $serverModel->del($params['id']);

        $this->redirect('/server/list?success='.urldecode('Сервер удалён'));
    }
}