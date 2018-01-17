<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/16/18
 * Time: 4:45 PM
 */
class ExampleModule{//类名必须和文件名一致,否则不会被init.php读取
    public function __construct(){//构造函数
        //啥都不用写就好
    }
    public function SetMP($Logger,$Encrypt,$Network,$Daemon,$Settings){//这里把Logger,Encrypt,Network,Daemon这几个核心的object给传递过来,直接可以调用,$Settings是一个array包含设置
        $this->Logger = $Logger;//把局部变量改为一个类里都可以用的诡异的变量
        $this->Encrypt = $Encrypt;
        $this->Network = $Network;
        $this->Daemon = $Daemon;
        $this->Settings = $Settings;
        $this->start();//这个函数名可以自定义
        return json_encode(array("这里填写返回消息,比如配置"));//返回配置信息2333
    }
    public function start(){//函数名可以自定义
        $this->Logger->PrintLine($this->Settings);//返回消息,具体参考./Logger.php
    }
}