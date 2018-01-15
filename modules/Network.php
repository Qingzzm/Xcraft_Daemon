<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/15/18
 * Time: 4:36 PM
 */
class Network{
    public function __construct(){

    }
    public function setMP($ip,$port,$interval){
        $this->ip = $ip;
        $this->port = $port;
        $this->interval = $interval;
        $this->http = new swoole_http_server($this->ip,$this->port);
        return json_encode(array("ip"=>$this->ip,"port"=>$this->port));
    }
    public function StartWeb(){
        while(1){
            $this->http->on('request', function ($request, $response) {
                $html = "<h1>Hello Swoole.</h1>";
                $response->end($html);
            });
            usleep($this->interval);
        }
    }
}