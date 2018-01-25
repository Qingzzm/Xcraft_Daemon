<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/15/18
 * Time: 9:09 AM
 */
class Security{
    /**
     * Security constructor.
     */
    public function __construct(){

    }

    /**
     * @param $method
     * @param $password
     * @return string
     */
    public function SetMP($method, $password){
        $this->method = $method;
        $this->password = $password;
        return json_encode(array("Method"=>$this->method,"Password"=>$this->password));
    }
    /* @Deprecated */
    public function Encode($data){
        return base64_encode(openssl_encrypt($data,$this->method,$this->password,OPENSSL_RAW_DATA));
    }
    /* @Deprecated */
    public function Decode($data){
        return rtrim(openssl_decrypt(base64_decode($data),$this->method,$this->password,OPENSSL_RAW_DATA));
    }

    /**
     * @param $plaintext
     * @return string
     */
    public function Encrypt($plaintext){
        $ivlen = openssl_cipher_iv_length($cipher=$this->method);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $this->password, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->password, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        return $ciphertext;
    }

    /**
     * @param $ciphertext
     * @return bool|string
     */
    public function Decrypt($ciphertext){
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher=$this->method);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->password, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->password, $as_binary=true);
        if (hash_equals($hmac, $calcmac)) {//PHP 5.6+ timing attack safe comparison安全保护
            return $original_plaintext;
        }else{
            return false;
        }
    }
    public function IsMatch($content){
        if(preg_match('/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']+$/',$content)){
            return true;
        }else{
            return false;
        }
    }
    public function IsBasicallyMatch($content){
        if(preg_match("^[1-9][0-9]*(\.[0-9]+)?$",$content)){
            return true;
        }else{
            return false;
        }
    }

}