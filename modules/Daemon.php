<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/16/18
 * Time: 10:02 AM
 */
class Daemon{
    public function __construct(){

    }
    public function SetMP($Logger,$Encrypt,$Password){
        $this->Logger = $Logger;
        $this->Encrypt = $Encrypt;
        $this->Password = $Password;
        return json_encode(array());
    }
    public function ReceiveConnection($action){
        $action = explode("/",$action);
        switch($action[0]){
            case "Verify":
                break;
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
                return "[PANIC]未找到预期的操作";
                break;
        }
    }
}