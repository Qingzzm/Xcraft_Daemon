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
     * @param $Security
     * @param $Password
     * @return string
     */
    public function SetMP($Logger,$Security,$Password,$UserControl){
        $this->Logger = $Logger;
        $this->Security = $Security;
        $this->Password = $Password;
        $this->UserControl = $UserControl;
        return json_encode(array("Password"=>$this->Password));
    }

    /**
     * @param $action
     * @return string
     */
    public function ReceiveConnection($action){
        $actions = explode("/",$action);
        if(isset($actions[1])) {
            if ($this->Verify($actions[1])) {
                switch ($actions[0]) {//第一层,分析动作是什么
                    case "NewUser":
                        if(isset($actions[2]) and isset($actions[3])) {
                            if($this->UserControl->NewUser($actions[2], $actions[3])){
                                $this->Logger->PrintLine("接受来自客户端的添加用户请求并且请求成功");
                                return "用户添加成功";
                            }else{
                                $this->Logger->PrintLine("接受来自客户端的添加用户请求并且请求失败");
                                return "用户添加失败";
                            }
                        }
                        break;
                    case "DeleteUser":
                        if(isset($actions[2])) {
                            if($this->UserControl->DeleteUser($actions[2])){
                                $this->Logger->PrintLine("接受来自客户端的删除用户请求并且请求成功");
                                return "用户删除成功";
                            }else{
                                $this->Logger->PrintLine("接受来自客户端的删除用户请求并且请求失败");
                                return "用户删除失败";
                            }
                        }
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
                        return "[WARNING]未找到预期的操作";
                        break;
                }
            } else {
                $this->Logger->PrintLine("对接密码发生错误!", 2);
                return "[ERROR]对接密码发生错误!";
            }
        }else{
            $this->Logger->PrintLine("对接密码不存在....!!", 2);
            return "[ERROR]对接密码不存在....!";
        }
    }

    /**
     * @param $DaemonPassword
     * @return bool
     */
    public function Verify($DaemonPassword){
        if($DaemonPassword == $this->Password){
            return true;
        }else {
            return false;
        }
    }
}