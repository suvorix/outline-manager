<?php
namespace App\Controllers;

use App\Controllers\ServerApiController;
use App\Models\Server;
use App\Models\ServerKey;
use App\Models\KeyStatistic;

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

    
    public function check_metrics( $params ) 
    {
        echo('<pre>');
        $serverModel = new Server();
        $serverKeyModel = new ServerKey();
        $keyStatisticModel = new KeyStatistic();

        $servers = $serverModel->get("SELECT id, apiUrl, certSha256 FROM servers WHERE status = 1");

        foreach($servers as $server) {
            $serverApiController = new ServerApiController($server['apiUrl'], $server['certSha256']);
            $metrics = $serverApiController->getMetrics('300s');
            if($metrics) {
                foreach($metrics['accessKeys'] as $metric_key) {
                    $key = $serverKeyModel->get("select id from server_keys where server_id = ? and key_id = ?", array($server['id'], $metric_key['accessKeyId']));
                    if(count($key) > 0){
                        $key = $key[0]['id'];

                        $dataTransferred = (int) $metric_key['dataTransferred']['bytes'];
                        $tunnelTime = (int) $metric_key['tunnelTime']['seconds'];
                        $lastTrafficSeen = (int) $metric_key['connection']['lastTrafficSeen'];
                        $peakDeviceCount = (int) $metric_key['connection']['peakDeviceCount']['data'];

                        if( !($dataTransferred == 0 && $tunnelTime == 0 && $lastTrafficSeen == 0 && $peakDeviceCount == 0) ) {
                            $insert = array(
                                'key_id' => $key,
                            );
                            if($dataTransferred > 0) { $insert['dataTransferred'] = $dataTransferred; }
                            if($tunnelTime > 0) { $insert['tunnelTime'] = $tunnelTime; }
                            if($lastTrafficSeen > 0) { $insert['lastTrafficSeen'] = date('Y-m-d H:i:s', $lastTrafficSeen); }
                            if($peakDeviceCount > 0) { $insert['peakDeviceCount'] = $peakDeviceCount; }

                            $keyStatisticModel->add($insert);
                            var_dump(array(
                                '$key' => $key,
                                '$dataTransferred' => $dataTransferred,
                                '$tunnelTime' => $tunnelTime,
                                '$lastTrafficSeen' => $lastTrafficSeen,
                                '$peakDeviceCount' => $peakDeviceCount,
                            ));
                        }
                    }
                }
            }
        }
    }
}