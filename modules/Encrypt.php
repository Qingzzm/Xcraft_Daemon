<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/15/18
 * Time: 9:09 AM
 */
class Encrypt{
    public function __construct($method,$password){
        $this->method = $method;
        $this->password = $password;
    }
    public function Encode($data,$password){
        return openssl_encrypt($data,$this->method,$this->password);
    }
    public function Decode($data,$method,$password){
        return openssl_encrypt($data,$this->method,$this->password);
    }
}