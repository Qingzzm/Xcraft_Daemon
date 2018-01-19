<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/18/18
 * Time: 6:50 AM
 */
class UserControl{
    /**
     * UserControl constructor.
     */
    public function __construct(){

    }

    /**
     * @param $Logger
     * @param $DIR
     * @return string
     */
    public function SetMP($Logger, $DIR){
        $this->Logger = $Logger;
        $this->DIR = $DIR;
        return json_encode(array("USERDATADIR"=>$DIR));
    }
    public function NewUser($account,$password){
        @mkdir($this->DIR.$account."/");
        file_put_contents($this->DIR.$account."/password",md5($password));
        if(@file_get_contents($this->DIR.$account."/password") == md5($password)){
            return true;
        }
        return false;
    }
    public function DeleteUser($account){
        unlink($this->DIR.$account."/password");
        @rmdir($this->DIR.$account."/");
        if(!file_exists($this->DIR.$account."/password")){
            return false;
        }
        return true;
    }
    public function Verify($account,$password){
        if(@file_get_contents($this->DIR.$account."/password") == md5($password)){
            return true;
        }
        return false;
    }
    public function GetUserInfo(){

    }
}