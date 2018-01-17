<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/16/18
 * Time: 10:02 AM
 */
class Daemon extends Thread{
    public function __construct(){

    }
    public function SetMP($Logger,$Encrypt){
        $this->Logger = $Logger;
        $this->Encrypt = $Encrypt;
        return json_encode(array());
    }
}