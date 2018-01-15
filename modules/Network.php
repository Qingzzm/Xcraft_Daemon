<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/15/18
 * Time: 4:36 PM
 */
class Network extends Thread{
    public function __construct(){

    }
    public function setMP($ip,$port){
        $this->ip = $ip;
        $this->port = $port;
        $this->http = new swoole_http_server($this->ip,$this->port);
        return json_encode(array("ip"=>$this->ip,"port"=>$this->port));
    }
    public function run(){

    }
}