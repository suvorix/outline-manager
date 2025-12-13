<?php
namespace App\Controllers;

use App\Controllers\ServerApiController;
use App\Models\Server;

class CronController
{
    public function __construct() {}

    public function check_servers( $params )
    {
        $serverModel = new Server();

        $servers = $serverModel->get("SELECT 
            id, 
            apiUrl, 
            certSha256,
            status,
            status_date_update,
            CASE 
                -- Для status 0 и 1 приоритет выше, если проверялись больше минуты назад
                WHEN status IN (0, 1) 
                THEN GREATEST(TIMESTAMPDIFF(SECOND, status_date_update, NOW()) - 50, 0)
                -- Для status -1 приоритет выше, если проверялись больше часа назад
                WHEN status = -1 
                THEN GREATEST(TIMESTAMPDIFF(SECOND, status_date_update, NOW()) - 3550, 0)
                ELSE 0
            END AS check_priority
        FROM servers 
        WHERE status IN (0, 1, -1)
        HAVING check_priority > 0
        ORDER BY 
            check_priority DESC, -- Сначала те, которые давно не проверялись
            CASE 
                WHEN status IN (0, 1) THEN 1 -- Более высокий приоритет у status 0 и 1
                WHEN status = -1 THEN 2
                ELSE 3
            END,
            id DESC
        LIMIT 100");

        foreach($servers as $server) {
            $serverApiController = new ServerApiController($server['apiUrl'], $server['certSha256']);
            $data_update = array('status_date_update' => date('Y-m-d H:i:s'));
            if($serverApiController->server() === null) {
                $data_update['status'] = -1;
            }
            $serverModel->edit($server['id'], $data_update);
        }
    }
}