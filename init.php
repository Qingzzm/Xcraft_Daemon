<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/14/18
 * Time: 4:47 PM
 */
//定义几个值....方便以后调用
define('XC_VERSION',"v1.0.0-beta.1");
define('OPENSOURCE',"GNU GENERAL PUBLIC LICENSE Version 3");
define('BASEDIR',dirname(__FILE__)."/");
define('MODULEDIR',BASEDIR."modules/");
define('CONFIGDIR',BASEDIR."config/");
define('DEPENDENCYDIR',BASEDIR."dependencies/");
define('JARDIR,BASEDIR',BASEDIR."jars/");
define('SERVERDIR',BASEDIR."servers/");
//因为啊,这个在读取modules之前没法使用输出函数啊...所以只能通过一个数组$StartingMessage[]去把预输出的信息都给梳理出来,启动之后立马把这些信息读取并且unset变量
$StartingMessage = array();
//预先修复目录
@mkdir(MODULEDIR);
@mkdir(CONFIGDIR);
@mkdir(DEPENDENCYDIR);
@mkdir(JARDIR);
@mkdir(SERVERDIR);
//读取config
$setting_file=CONFIGDIR."settings.json";
$module_file=CONFIGDIR."modules.json";
if(!file_exists($setting_file)) {
    $StartingMessage["NoConfig:settings.json"] = "找不到配置文件: settings.json,正在试图创建新文件";
    $settings=array(
        "TPS"=>60,
        "DaemonID"=>1,
        "DaemonIP"=>"127.0.0.1",
        "DaemonPort"=>18114,//新版Xcraft开始开发日期
        'worker_num' => 8,
        'max_request' => 10000,
        "Password"=>"January14,2018"
    );
    file_put_contents($setting_file,json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}else{
    $settings=json_decode(file_get_contents($setting_file),true);
    $StartingMessage["YesConfig:settings.json"] = "加载配置文件: settings.json";
    if($settings == null)
        die("FATAL ERROR(0)!\r\n");
}
$settings["Interval"]=round(1000/$settings["TPS"]);
if(!file_exists($module_file)) {
    $StartingMessage["NoConfig:modules.json"] = "找不到配置文件: modules.json,正在试图创建新文件";
    $modules=array(
        "Logger",
        "Minecraft",
        "Network",
        "Encrypt",
        "Daemon",
        "ExampleModule"
    );
    file_put_contents($module_file,json_encode($modules,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}else{
    $modules=json_decode(file_get_contents($module_file),true);
    $StartingMessage["YesConfig:modules.json"] = "加载配置文件: modules.json";
    if($modules == null)
        die("FATAL ERROR(0)!\r\n");
}
//开始挨个读取Modules
foreach($modules as $module){
    if(file_exists(MODULEDIR.$module.".php")){
        include(MODULEDIR.$module.".php");
        ${$module} = new $module;
        $StartingMessage["YesModule:".$module] = "加载模组文件: ".$module;
    }else{
        $StartingMessage["NoModule:".$module] = "找不到模组文件: ".$module.".php,Xcraft将无法加载".$module.",如果".$module."是核心Module,则Xcraft会报错崩溃";
    }
}
//检测核心Module是否存在
if(!isset($Logger) or !isset($Encrypt) or !isset($Network) or !isset($Daemon)){
    die("FATAL ERROR(1)!\r\n");
}
//Logger开始运行咯!
$Logger->PrintStartingMessages($StartingMessage,XC_VERSION);
$Logger->PrintLine("Logger配置: ".$Logger->SetMP());
$Logger->PrintLine("Encrypt配置: ".$Encrypt->SetMP("aes-128-cbc",$settings["Password"],$settings["IV"]));
$Logger->PrintLine("Network配置: ".$Network->SetMP($settings["DaemonIP"],$settings["DaemonPort"],$settings["Interval"],$settings['worker_num'],$settings['max_request'],$Logger,$Encrypt,XC_VERSION));
$Logger->PrintLine("Daemon配置: ". $Daemon->SetMP($Logger,$Encrypt));
foreach($modules as $module){
    if($module != "Logger" and $module != "Daemon" and $module != "Encrypt" and $module != "Network" and isset(${$module})){
        $Logger->PrintLine($module."配置: ".${$module}->SetMP($Logger,$Encrypt,$Network,$Daemon,$settings));
    }
}
unset($StartingMessage);
unset($setting_file);
unset($module_file);
$Network->StartWeb();