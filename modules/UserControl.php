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
        @mkdir($this->ListDIR);
        return json_encode(array("USERDATADIR"=>$DIR));
    }
    public function NewUser($account,$password){
        if(!is_dir($this->DIR.$account."/") and $this->Security->IsMatch($account) and $this->Security->IsMatch($password)) {
            @mkdir($this->DIR . $account . "/");
            file_put_contents($this->DIR . $account . "/password", md5($password));
            file_put_contents($this->DIR . $account . "/servers.json",json_encode(array()));
            if (@file_get_contents($this->DIR . $account . "/password") == md5($password) and file_exists($this->DIR . $account . "/servers.json")) {
                $this->Logger->PrintLine("成功添加新用户,用户信息:" . json_encode(array("account" => $account, "password" => $password), 0));
                return true;
            }
            $this->Logger->PrintLine("失败添加新用户,用户信息:" . json_encode(array("account" => $account, "password" => $password), 2));
            return false;
        }else{
            $this->Logger->PrintLine("出现了一个安全错误导致无法执行",5);
            return false;
        }
    }
    public function DeleteUser($account){
        if($this->Security->IsMatch($account)) {
            @unlink($this->DIR . $account . "/password");
            @unlink($this->DIR . $account . "/servers.json");
            @rmdir($this->DIR . $account . "/");
            if (file_exists($this->DIR . $account . "/password") or file_exists($this->DIR . $account . "/servers.json")) {
                $this->Logger->PrintLine("失败删除老用户,用户信息:" . json_encode(array("account" => $account), 0));
                return false;
            }
            $this->Logger->PrintLine("成功删除老用户,用户信息:" . json_encode(array("account" => $account), 0));
            return true;
        }else{
            $this->Logger->PrintLine("出现了一个安全错误导致无法执行",5);
            return false;
        }
    }
    public function Verify($account,$password){
        if(@file_get_contents($this->DIR.$account."/password") == md5($password)){
            return true;
        }
        return false;
    }
    public function GetUserInfo(){

    }
    public function GetServerInfoByID($id){
        if($this->Security->IsMatch($id) and file_exists($this->ListDIR.$id.".json")){
            $data = json_decode(file_get_contents($this->ListDIR.$id.".json"),true);
            if($this->Security->IsMatch($data)){
                return $data;
            }else{
                $this->Logger->PrintLine("出现了安全问题导致无法读取数据",5);
                return false;
            }
        }else{
            $this->Logger->PrintLine("不存在的ID,无法找到对应的数据或者出现了安全问题",5);
            return false;
        }
    }
}