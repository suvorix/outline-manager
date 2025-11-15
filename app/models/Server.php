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

    public function add($name, $apiUrl, $certSha256)
    {
        $data = array(
            'name' => $name,
            'apiUrl' => $apiUrl,
            'certSha256' => $certSha256,
        );
        return $this->insert('servers', array_keys($data), array_values($data));
    }

    public function getServers($offset = -1, $limit = -1)
    {
        $query = "select 
            id,
            name,
            apiUrl,
            certSha256,
            substring(apiUrl, 9, LOCATE(':', apiUrl, 7) - 9) as ip
        from servers order by id desc";
        if ( $offset != -1 && $limit != -1 ) { $query .= ' limit ' . $limit . ' offset ' . $offset; }
        return $this->get($query);
    }
}