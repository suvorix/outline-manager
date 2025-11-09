<?php
namespace App\Models;

use App\Core\Database;

abstract class Model
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function execute($query, $vars = array()) {
        $stmt = $this->db->prepare($query);
        $stmt->execute($vars);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}