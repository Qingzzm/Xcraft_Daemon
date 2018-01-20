<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/15/18
 * Time: 4:36 PM
 */
class Network{
    /**
     * Network constructor.
     */
    public function __construct(){

    }

    /**
     * @param $ip
     * @param $port
     * @param $interval
     * @param $worker_num
     * @param $max_request
     * @param $Logger
     * @param $Security
     * @param $Daemon
     * @param $Version
     * @return string
     */
    public function setMP($ip, $port, $interval, $worker_num, $max_request, $Logger, $Security, $Daemon, $Version){
        $this->ip = $ip;
        $this->port = $port;
        $this->interval = $interval;
        $this->worker_num = $worker_num;
        $this->max_request = $max_request;
        $this->Logger = $Logger;
        $this->Security = $Security;
        $this->Daemon = $Daemon;
        $this->Version = $Version;
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

    /**
     * @param $serv
     * @param $fd
     * @param $from_id
     * @param $data
     */
    public function response($serv, $fd, $from_id, $data){
        //获取data
        $datas = explode(" ",$data);
        $action = str_replace("/","",$datas[1]);//因为某些兼容问题已经被我日了
        $action = substr($datas[1],1);
        unset($datas);
        unset($data);
            //响应行
            $respData = $this->Security->Decrypt($this->Security->Encrypt($this->Daemon->ReceiveConnection($action)));//为了确保显示结果正常加密正常, 我选择加密后再解密,解密那个正式测试好了之后会删除的
            $response = array(
                'HTTP/1.1 200',
            );
            //响应头
            $headers = array(
                'Server'=>'Xcraft'.$this->Version,
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
        $this->Logger->PrintLine("收到一条http请求,from_id:".$from_id.",参数:{".$action."}");

    }

    /**
     *
     */
    public function StartWeb(){
        $this->serv->start();
    }
}