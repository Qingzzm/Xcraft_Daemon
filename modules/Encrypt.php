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
    public function SetMP($method,$password,$IV){
        $this->method = $method;
        $this->password = $password;
        $this->IV = $IV;
        return json_encode(array("Method"=>$this->method,"Password"=>$this->password));
    }
    public function Encode($data){
        $iv=random_bytes(16);//类似于盐值的一个东西2333
        return base64_encode(openssl_encrypt($data,$this->method,$this->password,OPENSSL_RAW_DATA,$this->IV));
    }
    public function Decode($data){
        return rtrim(openssl_decrypt(base64_decode($data),$this->method,$this->password,OPENSSL_RAW_DATA,$this->IV));
    }
}