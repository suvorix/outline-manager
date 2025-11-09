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
        require_once BASE_PATH . '/views/' . $page . '.php';
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
    
    public function login()
    {
        if( $this->checkAuth() ) { $this->redirect('/'); }
        $this->pageInfo['title'] = 'Авторизация | ' . $this->pageInfo['title'];
        $this->view('login', 'empty');
    }
    
    public function dashboard()
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        
        $modelServer = new Server();

        $this->pageInfo['server_count'] = $modelServer->count();

        $this->pageInfo['title'] = 'Панель управления | ' . $this->pageInfo['title'];
        $this->view('dashboard');
    }
    
    public function servers()
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Сервера | ' . $this->pageInfo['title'];
        $this->view('servers');
    }
    
    public function addServer()
    {
        if( !$this->checkAuth() ) { $this->redirect('/login'); }
        $this->pageInfo['title'] = 'Сервера | ' . $this->pageInfo['title'];
        $this->view('add-server');
    }
}