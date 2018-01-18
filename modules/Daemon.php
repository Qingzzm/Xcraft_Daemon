<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/16/18
 * Time: 10:02 AM
 */
class Daemon{
    /**
     * Daemon constructor.
     */
    public function __construct(){

    }

    /**
     * @param $Logger
     * @param $Encrypt
     * @param $Password
     * @return string
     */
    public function SetMP($Logger, $Encrypt, $Password){
        $this->Logger = $Logger;
        $this->Encrypt = $Encrypt;
        $this->Password = $Password;
        return json_encode(array());
    }

    /**
     * @param $action
     * @return string
     */
    public function ReceiveConnection($action){
        $actions = explode("/",$action);
        if($this->Verify($actions[1])) {
            switch ($actions[0]) {//第一层,分析动作是什么
                case "NewUser":
                    break;
                case "DeleteUser":
                    break;
                case "NewServer":
                    break;
                case "DeleteServer":
                    break;
                case "StartServer":
                    break;
                case "StopServer":
                    break;
                case "RestartServer":
                    break;
                case "GetCommandLine":
                    break;
                default:
                    $this->Logger->PrintLine("未找到预期的操作", 1);
                    return "[PANIC]未找到预期的操作";
                    break;
            }
        }else{
            $this->Logger->PrintLine("对接密码发生错误!", 2);
            return "[PANIC]对接密码发生错误!";
        }
    }

    /**
     * @param $DaemonPassword
     * @return bool
     */
    public function Verify($DaemonPassword){
        if($DaemonPassword == $this->Password){
            return true;
        }
        return false;
    }
}