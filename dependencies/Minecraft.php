<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/21/18
 * Time: 1:21 PM
 */
class Minecraft extends Thread{
    public function __construct($data,$Logger){
        $this->DoShutDown=0;
        if($data != false){
            $this->data = $data;
            $this->id= $data["id"];
        }else{
            $this->DoShutDown=1;
        }
        $this->Logger = $Logger;
    }
    public function run(){
        if($this->DoShutDown){
            die("[意外]:dhdj!你又写bug!,赶紧给我修复了!!!\r\n");
        }
        $this->Logger->PrintLine("服务器已经准备就绪,启动数据:".json_encode($this->data));
    }
}