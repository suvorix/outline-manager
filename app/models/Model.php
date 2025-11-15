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
        return $stmt;
    }

    public function get($query, $vars = array()) {
        $stmt = $this->execute($query, $vars);
        return $stmt->fetchAll();
    }

    public function insert($table, $fields, $values) {
        $query = "insert into {$table} (" . implode(', ', $fields) . ") values (" . implode(', ', array_fill(0, count($values), '?')) . ")";
        $stmt = $this->execute($query, $values);
        return $this->db->lastInsertId();
    }
}