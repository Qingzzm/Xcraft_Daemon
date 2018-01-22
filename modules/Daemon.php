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
    public function SetMP($Logger,$Security,$Password,$UserControl,$dir){
        include(DEPENDENCYDIR."Minecraft.php");
        $this->Logger = $Logger;
        $this->Security = $Security;
        $this->Password = $Password;
        $this->UserControl = $UserControl;
        $this->DIR = $dir;
        $this->Logger->PrintLine("读取服务器列表中");
        if(!file_exists($this->DIR."serverlist.json")){
            $this->Logger->PrintLine("服务器列表不存在,正在试图创建",3);
            $Serverlist = array();
            file_put_contents($this->DIR."serverlist.json",json_encode($Serverlist,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        }else{
            $Serverlist = json_decode(file_get_contents($this->DIR."serverlist.json"),true);
            $this->Logger->PrintLine("服务器列表读取成功");
        }
        if(isset($Serverlist)){
            foreach($Serverlist as $SingleServer){
                $this->Logger->PrintLine("服务器ID: ".$SingleServer." 正在开启");
                $pool[] = new Minecraft($SingleServer,$this->Logger);
            }
            if(isset($pool)) {
                foreach ($pool as $singlethread) {
                    $singlethread->start();
                }
                $this->Logger->PrintLine("所有服务器均已尝试开启");
            }else{
                $this->Logger->PrintLine("无法开启任何服务器",6);
            }
        }else{
            $this->Logger->PrintLine("服务器列表内没有任何服务器",1);
        }
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
                                return $this->Logger->PrintJson("用户添加成功",0,true);
                            }else{
                                $this->Logger->PrintLine("接受来自客户端的添加用户请求并且请求失败");
                                return $this->Logger->PrintJson("用户添加失败",2,false);
                            }
                        }
                        break;
                    case "DeleteUser":
                        if(isset($actions[2])) {
                            if($this->UserControl->DeleteUser($actions[2])){
                                $this->Logger->PrintLine("接受来自客户端的删除用户请求并且请求成功");
                                return $this->Logger->PrintJson("用户删除成功",0,true);
                            }else{
                                $this->Logger->PrintLine("接受来自客户端的删除用户请求并且请求失败");
                                return $this->Logger->PrintJson("用户删除失败",2,false);
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
                        return $this->Logger->PrintJson("未找到预期的操作",2,false);
                        break;
                }
            } else {
                $this->Logger->PrintLine("对接密码发生错误!", 2);
                return $this->Logger->PrintJson("对接密码发生错误!",2,false);
            }
        }else{
            $this->Logger->PrintLine("对接密码不存在....!!", 2);
            return $this->Logger->PrintJson("对接密码不存在....!",2,false);
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