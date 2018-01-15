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
    public function setMP($ip,$port,$interval,$worker_num,$max_request){
        $this->ip = $ip;
        $this->port = $port;
        $this->interval = $interval;
        $this->worker_num = $worker_num;
        $this->max_request= $max_request;
        //$this->http = new swoole_http_server($this->ip,$this->port);
        $this->serv = new swoole_server($this->ip, $this->port);
        $this->serv->set(array(
            'worker_num' => $this->worker_num,
            'daemonize' => 0,
            'max_request' => $this->max_request,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
        ));
        $this->serv->on('Receive', function($serv, $fd, $from_id, $data){
            $respData='<h1>Hello Swoole.</h1>';
            response($serv,$fd,$respData);//封装并发送HTTP响应报文
        });
        return json_encode(array("ip"=>$this->ip,"port"=>$this->port));
    }
    public function StartWeb(){
        $this->serv->start();
        /*while(1){
            $this->http->on('request', function ($request, $response) {
                $html = "<h1>Hello Swoole.</h1>";
                $response->end($html);
            });
            usleep($this->interval);
        }*/
    }
}