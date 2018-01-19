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
        if(!is_dir($this->DIR.$account."/")) {
            @mkdir($this->DIR . $account . "/");
            file_put_contents($this->DIR . $account . "/password", md5($password));
            if (@file_get_contents($this->DIR . $account . "/password") == md5($password)) {
                $this->Logger->PrintLine("成功添加新用户,用户信息:" . json_encode(array("account" => $account, "password" => $password), 0));
                return true;
            }
            $this->Logger->PrintLine("失败添加新用户,用户信息:" . json_encode(array("account" => $account, "password" => $password), 2));
            return false;
        }else{
            $this->Logger->PrintLine("正在试图添加一个已经存在的用户",5);
            return false;
        }
    }
    public function DeleteUser($account){
        @unlink($this->DIR.$account."/password");
        @rmdir($this->DIR.$account."/");
        if(!file_exists($this->DIR.$account."/password")){
            $this->Logger->PrintLine("失败删除老用户,用户信息:".json_encode(array("account"=>$account),0));
            return false;
        }
        $this->Logger->PrintLine("成功删除老用户,用户信息:".json_encode(array("account"=>$account),0));
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