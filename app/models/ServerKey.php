<?php
namespace App\Models;

class ServerKey extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function count()
    {
        return $this->get('select 
            count(1) as count, 
            count(case when s.status = 1 then 1 end) as count_active 
        from server_keys k
        left join servers s on s.id = k.server_id')[0];
    }

    public function add($key, $data = array())
    {
        $inserted = array(
            'key_id' => $key['id'],
            'key_name' => $key['name'],
            'key_password' => $key['password'],
            'key_port' => $key['port'],
            'key_method' => $key['method'],
            'key_accessUrl' => $key['accessUrl'],
        );

        $inserted['server_id'] = isset($data['server_id']) ? $data['server_id'] : 0;
        
        if(isset($data['date_end'])) {
            $inserted['date_end'] = $data['date_end'];
        }
        
        return $this->insert('server_keys', $inserted);
    }

    public function edit($id, $data)
    {
        return $this->update('server_keys', $data, $id);
    }

    public function del($id)
    {
        return $this->execute('delete from server_keys where id = ?', array($id));
    }

    public function getKeys($server_id = false, $offset = -1, $limit = -1)
    {
        $query = "select 
            id,
            DATE_FORMAT(date_add, '%d.%m.%Y %H:%i') as date_add,
            DATE_FORMAT(date_end, '%d.%m.%Y') as date_end,
            server_id,
            key_id,
            key_name,
            key_method,
            key_accessUrl
        from server_keys " . ($server_id !== false ? "where server_id = {$server_id}" : "") . " order by date_add desc";
        if ( $offset != -1 && $limit != -1 ) { $query .= ' limit ' . $limit . ' offset ' . $offset; }
        return $this->get($query);
    }

    public function getKey($id)
    {
        $query = "select 
            *
        from server_keys where id = ?";
        $data = $this->get($query, array($id));
        if(count($data) > 0) {
            return $data[0];
        }
        return false;
    }
}