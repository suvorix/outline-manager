<?php
namespace App\Models;

class Server extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function count()
    {
        return $this->execute('select count(1) as count from servers')['count'];
    }
}