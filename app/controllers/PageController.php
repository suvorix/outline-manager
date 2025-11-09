<?php
namespace App\Controllers;

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
    
    public function login()
    {
        $this->pageInfo['title'] = 'Авторизация | ' . $this->pageInfo['title'];
        $this->view('login', 'empty');
    }
    
    public function dashboard()
    {
        $this->pageInfo['title'] = 'Панель управления | ' . $this->pageInfo['title'];
        $this->view('dashboard');
    }
    
    public function servers()
    {
        $this->pageInfo['title'] = 'Сервера | ' . $this->pageInfo['title'];
        $this->view('servers');
    }
    
    public function addServer()
    {
        $this->pageInfo['title'] = 'Сервера | ' . $this->pageInfo['title'];
        $this->view('add-server');
    }
}