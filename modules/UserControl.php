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
     * @param $Security
     * @param $DIR
     * @return string
     */
    public function SetMP($Logger,$Security,$DIR,$dataDIR){
        $this->Logger = $Logger;
        $this->Security = $Security;
        $this->DIR = $DIR;
        $this->dataDIR = $dataDIR;
        $this->ListDIR = $this->dataDIR."list/";
        @mkdir($this->ListDIR,0777);
        if(!file_exists($this->DIR."id")){
            file_put_contents($this->DIR."id",1);
            $this->Logger->PrintLine("UserControl必要文件缺失,正在尝试初始化",3);
        }
        return json_encode(array("USERDATADIR"=>$DIR,"DATADIR"=>$this->dataDIR));
    }

    public function GetCurrentID(){
        return file_get_contents($this->DIR."id");
    }
    public function UpdateCurrentID(){
        file_put_contents($this->DIR."id",file_get_contents($this->DIR."id")+1);
    }
    public function NewUser($account,$password){
        if(!file_exists($accountFile = $this->DIR.$account.".json") and $this->Security->IsMatch($account) and $this->Security->IsMatch($password)){
            @file_put_contents($accountFile,json_encode(array("id"=>$this->GetCurrentID(),"password"=>md5($password))));
            $this->UpdateCurrentID();
            if(file_exists($accountFile)){
                $this->Logger->PrintLine("成功添加新用户,用户信息:" . json_encode(array("account" => $account, "password" => $password)),233);
                return true;
            }else{
                $this->Logger->PrintLine("失败添加新用户,用户信息:" . json_encode(array("account" => $account, "password" => $password)),2);
                return false;
            }
        }else{
            $this->Logger->PrintLine("出现了一个安全错误导致无法执行",5);
            return false;
        }
    }
    public function DeleteUser($account){
        if($this->Security->IsMatch($account)) {
            @unlink($this->DIR . $account . ".json");
            if (file_exists($this->DIR . $account . ".json")) {
                $this->Logger->PrintLine("失败删除老用户,用户信息:" . json_encode(array("account" => $account)),0);
                return false;
            }
            $this->Logger->PrintLine("成功删除老用户,用户信息:" . json_encode(array("account" => $account)),233);
            return true;
        }else{
            $this->Logger->PrintLine("出现了一个安全错误导致无法执行",5);
            return false;
        }
    }
    public function Verify($account,$password){
        $info=$this->GetUserInfo($account);
        if($info["password"] == md5($password)){
            return true;
        }
        return false;
    }
    public function GetUserServers($account){

    }
    public function GetUserInfo($account){
        if(file_exists($this->DIR . $account . ".json")) {
            return json_decode(file_get_contents($this->DIR . $account . ".json"), true);
        }else{
            return false;
        }
    }
    public function UpdateUserInfo($account,$password){
        $UserInfo=$this->GetUserInfo($account);
        if($UserInfo !== false){
            $UserInfo["password"]=$password;
            file_put_contents($this->DIR . $account . ".json",json_encode($UserInfo));
            $this->Logger->PrintLine("成功的更新了用户的信息",233);
            return true;
        }else{
            $this->Logger->PrintLine("出现了一个安全错误导致无法执行",5);
            return false;
        }
    }
    public function IsUser($account){
        if(file_exists($this->DIR . $account . ".json")){
            return true;
        }else{
            return false;
        }
    }
}