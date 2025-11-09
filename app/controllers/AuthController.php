<?php
namespace App\Controllers;

class AuthController
{
    public function __construct() {}
    
    public function checkPassword($password)
    {
        return APP_PASSWORD === $password;
    }
    
    public function getApiKey()
    {
        return hash('sha256', APP_PASSWORD_SALT . APP_PASSWORD . APP_PASSWORD_SALT);
    }
    
    public function checkApiKey($api_key)
    {
        return $this->getApiKey() === $api_key;
    }
    
    public function checkAuth()
    {
        return $_SESSION['auth'] === true && $_SESSION['api_key'] === $this->getApiKey();
    }

    public function login()
    {
        if( isset($_POST['password']) ) {
            if( $this->checkPassword($_POST['password']) ) {
                $_SESSION['auth'] = true;
                $_SESSION['api_key'] = $this->getApiKey();
            }
        }
        header('Location: /');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }
}