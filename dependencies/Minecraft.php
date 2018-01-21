<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/21/18
 * Time: 1:21 PM
 */
class Minecraft extends Thread{
    public function __construct($id){
        $this->id = $id;
    }
    public function run(){
        echo "看似成功的开启测试,id:".$this->id;
    }
}