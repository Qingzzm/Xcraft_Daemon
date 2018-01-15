<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/15/18
 * Time: 9:09 AM
 */
class Encrypt{
    public function __construct(){

    }
    public function SetMP($method,$password){
        $this->method = $method;
        $this->password = $password;
        return json_encode(array("Method"=>$this->method,"Password"=>$this->password));
    }
    public function Encode($data){
        return base64_encode(openssl_encrypt($data,$this->method,$this->password));
    }
    public function Decode($data){
        return base64_decode(openssl_encrypt($data,$this->method,$this->password));
    }
}