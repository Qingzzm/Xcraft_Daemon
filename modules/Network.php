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
    public function setMP($ip,$port,$interval,$worker_num,$max_request,$Logger){
        $this->ip = $ip;
        $this->port = $port;
        $this->interval = $interval;
        $this->worker_num = $worker_num;
        $this->max_request= $max_request;
        $this->Logger=$Logger;
        $this->serv = new swoole_server($this->ip, $this->port);
        $this->serv->set(array(
            'worker_num' => $this->worker_num,
            'daemonize' => 0,
            'max_request' => $this->max_request,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
        ));
        $this->serv->on('Receive', function($serv, $fd, $from_id, $data){
            $this->response($serv,$fd,$from_id,$data);//封装并发送HTTP响应报文
        });
        return json_encode(array("ip"=>$this->ip,"port"=>$this->port));
    }
    public function response($serv,$fd,$from_id,$data){
            //响应行
            $respData = 'Welcome!!!!';
            $response = array(
                'HTTP/1.1 200',
            );
            //响应头
            $headers = array(
                'Server'=>'XcraftVer1.0.0-beta.1',
                'Content-Type'=>'text/html;charset=utf8',
                'Content-Length'=>strlen($respData),
            );
            foreach($headers as $key=>$val){
                $response[] = $key.':'.$val;
            }
            //空行
            $response[] = '';
            //响应体
            $response[] = $respData;
            $send_data = join("\r\n",$response);
            $serv->send($fd, $send_data);
        $this->Logger->PrintLine("收到一条消息,from_id:".$from_id."data:".$data);

    }
    public function StartWeb(){
        $this->serv->start();
    }
}