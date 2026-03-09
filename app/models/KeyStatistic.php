<?php
namespace App\Models;

class KeyStatistic extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data = array())
    {
        $inserted = array(
            'key_id' => $data['key_id'],
        );

        if(isset($data['tunnelTime'])) {
            $inserted['tunnelTime'] = $data['tunnelTime'];
        }
        if(isset($data['dataTransferred'])) {
            $inserted['dataTransferred'] = $data['dataTransferred'];
        }
        if(isset($data['lastTrafficSeen'])) {
            $inserted['lastTrafficSeen'] = $data['lastTrafficSeen'];
        }
        if(isset($data['peakDeviceCount'])) {
            $inserted['peakDeviceCount'] = $data['peakDeviceCount'];
        }
        
        return $this->insert('key_statistics', $inserted);
    }

    public function edit($id, $data)
    {
        return $this->update('key_statistics', $data, $id);
    }

    public function del($id)
    {
        return $this->execute('delete from key_statistics where id = ?', array($id));
    }

    public function getStatistictLast5min()
    {
        $data = $this->get("SELECT
            avg(COALESCE(dataTransferred, 0) / COALESCE(tunnelTime, 1)) as avg_speed,
            sum(COALESCE(peakDeviceCount, 0)) as device_online
        FROM key_statistics
        WHERE date_add >= NOW() - INTERVAL 5 MINUTE");

        $device_online = $data[0]['device_online'] != null ? $data[0]['device_online'] : 0;
        $speed = $data[0]['avg_speed'] != null ? $data[0]['avg_speed'] : 0;
        $speed_type='байт/с';
        if($speed >= 1024) {
            $speed = $speed / 1024; 
            $speed_type='Кб/с';
        }
        if($speed >= 1024) {
            $speed = $speed / 1024;
            $speed_type='Мб/с';
        }

        $speed = round($speed, 2);

        return array(
            'device_online' => $device_online,
            'speed' => array(
                'data' => $speed,
                'type' => $speed_type
            )
        );
    }
}