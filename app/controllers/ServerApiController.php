<?php
namespace App\Controllers;

// Documentation: https://redocly.github.io/redoc/?url=https://raw.githubusercontent.com/Jigsaw-Code/outline-server/master/src/shadowbox/server/api.yml

class ServerApiController
{
    private $isSaveErrorLog = true;

    private $apiUrl = false;
    private $certSha256 = false;

    // List of encryption methods
    public $listMethods = array(
        // AEAD ciphers (recommended, more secure)
        'chacha20-ietf-poly1305', // Recommended for most devices
        'aes-128-gcm',
        'aes-192-gcm',
        'aes-256-gcm',
        // Stream ciphers (outdated, less secure)
        'aes-128-cfb',
        'aes-192-cfb',
        'aes-256-cfb',
        'aes-128-ctr',
        'aes-192-ctr',
        'aes-256-ctr',
        'bf-cfb',
        'camellia-128-cfb',
        'camellia-192-cfb',
        'camellia-256-cfb',
        'rc4-md5',
        'salsa20',
        'chacha20',
        'chacha20-ietf',
        // Special options
        'plain',
        'none',
    );

    public function __construct( $apiUrl, $certSha256 ) {
        $this->apiUrl = $apiUrl;
        $this->certSha256 = $certSha256;
    }

    private function request( $method, $url, $data_array = array() ) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data_array),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        curl_close($curl);
        
        if($httpCode == 200) {
            $response = json_decode($response, true);
        }

        return array(
            'response' => $response,
            'http_code' => $httpCode,
            'error' => $error
        );
    }

    // Function for save error log
    private function saveErrorLog ( $function, $info ) {
        if($this->isSaveErrorLog) {
            if(is_array($info)) { 
                $info = json_encode($info);
            }
            $f = fopen(BASE_PATH . '/storage/logs/ServerApiController.log', 'a');
            fwrite($f, date('[Y-m-d H:i:s]') . ' FUNCTION:' . $function . ' - ' . $info . PHP_EOL);
            fclose($f);
        }
    }

    // [START] Server

    // Returns information about the server
    public function server() {
        $response = $this->request( 'GET', '/server' );
        if($response['http_code'] == 200) {
            return $response['response'];
        }

        $this->saveErrorLog(__FUNCTION__, $response);
    }

    public function changeServer( $data ) {
        // Changes the hostname for access keys. Must be a valid hostname or IP address. If it's a hostname, DNS must be set up independently of this API.
        if( isset($data['hostname']) ) {
            $response = $this->request( 'PUT', '/server/hostname-for-access-keys', array('hostname' => $data['hostname']) );
            if($response['http_code'] == 204) { return true; }
            $this->saveErrorLog(__FUNCTION__ . ':hostname', $response);
            return false;
        }
        // Renames the server
        else if( isset($data['name']) ) {
            $response = $this->request( 'PUT', '/name', array('name' => $data['name']) );
            if($response['http_code'] == 204) { return true; }
            $this->saveErrorLog(__FUNCTION__ . ':name', $response);
            return false;
        }
        
        $this->saveErrorLog(__FUNCTION__, '$data not found change info');
    }

    // Returns whether metrics is being shared
    public function getEnableMetric() {
        $response = $this->request( 'GET', '/metrics/enabled' );
        if($response['http_code'] == 200) {
            return $response['response']['metricsEnabled'];
        }

        $this->saveErrorLog(__FUNCTION__, $response);
    }

    // Enables or disables sharing of metrics
    public function setEnableMetric( $value ) {
        $errorInfo = '';
        if(is_bool($value)) {
            $response = $this->request( 'PUT', '/metrics/enabled', array('metricsEnabled' => $value) );
            if($response['http_code'] == 204) { return true; }
            $errorInfo = $response;
        }
        else {
            $errorInfo = '$value is not bool';
        }
        
        $this->saveErrorLog(__FUNCTION__, $errorInfo);
        return false;
    }

    // [END] Server

    // [START] Access Key

    // Changes the default port for newly created access
    public function setNewPort( $port ) {
        $errorInfo = '';
        if(is_int($port)) {
            if($port >= 1 && $port <= 65535) {
                $response = $this->request( 'PUT', '/server/port-for-new-access-keys', array('port' => $port) );
                if($response['http_code'] == 204) { return true; }
                $errorInfo = $response;
            }
            else { $errorInfo = '$port is not between 1 and 65535'; }
        }
        else { $errorInfo = '$port is not int'; }

        $this->saveErrorLog(__FUNCTION__, $errorInfo);
        return false;
    }

    // Sets a data transfer limit for all access keys
    public function setLimitBytes( $bytes ) {
        $errorInfo = '';
        if(is_int($bytes)) {
            if($bytes >= 0) {
                $response = $this->request( 'PUT', '/server/access-key-data-limit', array('limit' => array('bytes' => $bytes)) );
                if($response['http_code'] == 204) { return true; }
                $errorInfo = $response;
            }
            else { $errorInfo = '$bytes should be >= 0'; }
        }
        else { $errorInfo = '$bytes is not int'; }

        $this->saveErrorLog(__FUNCTION__, $errorInfo);
        return false;
    }

    // Removes the access key data limit, lifting data transfer restrictions on all access keys.
    public function delLimitBytes( ) {
        $response = $this->request( 'DELETE', '/server/access-key-data-limit' );
        if($response['http_code'] == 204) { return true; }

        $this->saveErrorLog(__FUNCTION__, $response);
        return false;
    }

    // Creates a new access key
    public function createAccessKey( $data = array() ) {
        // Example
        // $data = array('name' => 'TestUser', 'method' => 'chacha20-ietf-poly1305', 'password' => 'TestPassword', 'port' => 12345, 'limit' => array('bytes' => 1000) );
        // or $data = array('id' => 'test', 'name' => 'TestUser', 'method' => 'chacha20-ietf-poly1305', 'password' => 'TestPassword', 'port' => 12345, 'limit' => array('bytes' => 1000) );
        $method = 'POST';
        $url = '/access-keys';

        if(isset($data['id'])) { 
            // Creates a new access key with a specific identifer
            $method = 'PUT'; 
            $url = '/access-keys/' . $data['id']; 
        }

        $response = $this->request( $method, $url, $data );
        if($response['http_code'] == 201) { return $response['response']; }

        $this->saveErrorLog(__FUNCTION__, $response);
        return false;
    }

    // Get info access keys
    public function getAccessKey( $id = -1 ) {
        // Lists the access keys
        if($id == -1) {
            $response = $this->request( 'GET', '/access-keys' );
            if($response['http_code'] == 200) { return $response['response']; }
            $this->saveErrorLog(__FUNCTION__, $response);
            return false;
        }
        // Get an access key
        else {
            $response = $this->request( 'GET', '/access-keys/' . $id );
            if($response['http_code'] == 200) { return $response['response']; }
            $this->saveErrorLog(__FUNCTION__, $response);
            return false;
        }
    }

    // Deletes an access key
    public function delAccessKey( $id ) {
        $response = $this->request( 'DELETE', '/access-keys/' . $id, array('id' => $id) );
        if($response['http_code'] == 204) { return true; }
        $this->saveErrorLog(__FUNCTION__, $response);
        return false;
    }
    
    public function changeAccessKey( $id, $data ) {
        // Renames an access key
        if(isset($data['name'])) {
            $response = $this->request( 'PUT', '/access-keys/' . $id . '/name', array('name' => $data['name']) );
            if($response['http_code'] == 204) { return true; }
            $this->saveErrorLog(__FUNCTION__. ':name', $response);
            return false;
        }
        else if(isset($data['data-limit'])) {
            // Removes the data limit on the given access key
            if($data['data-limit'] == -1) {
                $response = $this->request( 'DELETE', '/access-keys/' . $id . '/data-limit', array('id' => $id) );
                if($response['http_code'] == 204) { return true; }
                $this->saveErrorLog(__FUNCTION__. ':data-limit:del', $response);
                return false;
            }
            // Sets a data limit for the given access key
            else {
                $response = $this->request( 'PUT', '/access-keys/' . $id . '/data-limit', array('limit' => array('bytes' => $data['data-limit'])) );
                if($response['http_code'] == 204) { return true; }
                $this->saveErrorLog(__FUNCTION__. ':data-limit:set', $response);
                return false;
            }
        }
    }
    
    // Returns the data transferred per access key
    public function getMetricsTransfer( ) {
        $response = $this->request( 'GET', '/metrics/transfer' );
        if($response['http_code'] == 200) { return $response['response']; }
        $this->saveErrorLog(__FUNCTION__, $response);
        return false;
    }

    // [END] Access Key

    // [START] Experimental

    // Display server metric information
    // since: Required time range filter (e.g., "24h", "7d", "30d", or ISO timestamp)
    public function getMetrics( $since ) {
        $response = $this->request( 'GET', '/experimental/server/metrics?since=' . $since );
        if($response['http_code'] == 200) { return $response['response']; }
        $this->saveErrorLog(__FUNCTION__, $response);
        return false;
    }

    // [END] Experimental

    // [START] Limit

    public function changeLimitForAllAccessKey($bytes = -1) {
        // Removes the access key data limit, lifting data transfer restrictions on all access keys
        if($bytes == -1) {
            $response = $this->request( 'DELETE', '/server/access-key-data-limit' );
            if($response['http_code'] == 204) { return true; }
            $this->saveErrorLog(__FUNCTION__. ':data-limit:del', $response);
            return false;
        }
        // Sets a data transfer limit for all access keys
        else {
            $response = $this->request( 'PUT', '/server/access-key-data-limit', array('limit' => array('bytes' => $bytes)) );
            if($response['http_code'] == 204) { return true; }
            $this->saveErrorLog(__FUNCTION__. ':data-limit:set', $response);
            return false;
        }
    }

    // [END] Limit
}