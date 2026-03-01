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
        return $this->get('select count(1) as count from servers')[0]['count'];
    }

    public function add($name, $apiUrl, $certSha256, $key_limit)
    {
        return $this->insert('servers', array(
            'name' => $name,
            'apiUrl' => $apiUrl,
            'certSha256' => $certSha256,
            'key_limit' => $key_limit,
            'status' => 1,
        ));
    }

    public function edit($id, $data)
    {
        return $this->update('servers', $data, $id);
    }

    public function del($id)
    {
        return $this->execute('delete from servers where id = ?', array($id));
    }

    public function getServers($offset = -1, $limit = -1)
    {
        $query = "select 
            id,
            DATE_FORMAT(date_add, '%d.%m.%Y %H:%i') as date_add,
            name,
            apiUrl,
            certSha256,
            substring(apiUrl, 9, LOCATE(':', apiUrl, 7) - 9) as ip,
            status,
            case status
                when -1 then 'Нет связи'
                when 0 then 'На проверке'
                when 1 then 'Работает'
            end as status_name,
            TIMESTAMPDIFF(MINUTE, status_date_update, NOW()) as status_date_update_min,
            key_limit,
            (select count(1) from server_keys where server_id = s.id) as key_count
        from servers s order by id desc";
        if ( $offset != -1 && $limit != -1 ) { $query .= ' limit ' . $limit . ' offset ' . $offset; }
        return $this->get($query);
    }

    public function getServer($id)
    {
        $query = "select * from servers where id = ?";
        $data = $this->get($query, array($id));
        if(count($data) > 0) {
            return $data[0];
        }
        return false;
    }
}