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

    public function insert($table, $data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $query = "insert into {$table} (" . implode(', ', $fields) . ") values (" . implode(', ', array_fill(0, count($values), '?')) . ")";
        $stmt = $this->execute($query, $values);
        return $this->db->lastInsertId();
    }

    public function update($table, $data, $id) {
        $query = "update {$table} set ";
        $query .= implode(' = ?, ', array_keys($data)) . " = ? ";
        $query .= "where id = ?";

        $data = array_values($data);
        array_push($data, $id);

        $stmt = $this->execute($query, $data);
    }
}