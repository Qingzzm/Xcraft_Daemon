<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/16/18
 * Time: 10:02 AM
 */
class Daemon{
    public function __construct(){

    }
    public function SetMP($Logger,$Encrypt){
        $this->Logger = $Logger;
        $this->Encrypt = $Encrypt;
        return json_encode(array());
    }
    public function ReceiveConnection(){
        return "开始编写了2333";
    }
}