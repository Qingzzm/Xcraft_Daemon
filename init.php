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
define('JARDIR',BASEDIR."jars/");
define('SERVERDIR',BASEDIR."servers/");
define('USERDATADIR',BASEDIR."users/");
define('DATADIR',BASEDIR."datas/");
//因为啊,这个在读取modules之前没法使用输出函数啊...所以只能通过一个数组$StartingMessage[]去把预输出的信息都给梳理出来,启动之后立马把这些信息读取并且unset变量
$StartingMessage = array();
//预先修复目录
@mkdir(MODULEDIR,0777);
@mkdir(CONFIGDIR,0777);
@mkdir(DEPENDENCYDIR,0777);
@mkdir(JARDIR,0777);
@mkdir(SERVERDIR,0777);
@mkdir(USERDATADIR,0777);
@mkdir(DATADIR,0777);
//读取config
for($x=1;$x<=500;$x++){
	for($y=1;$y<=50;$y++){
		echo "dhdj扔彩蛋";
}
echo "\r\n";
}
$setting_file=CONFIGDIR."settings.json";
$module_file=CONFIGDIR."modules.json";
if(!file_exists($setting_file)) {
    $StartingMessage["NoConfig:settings.json"] = array("找不到配置文件: settings.json,正在试图创建新文件",1);
    $settings=array(
        "TPS"=>60,
        "DaemonID"=>1,
        "DaemonIP"=>"127.0.0.1",
        "DaemonPort"=>18114,
        "DaemonPassword" => "January18,2018",//DAEMON链接密码,默认为我当时正在写这个的日期
        "AESPassword" => "January14,2018",//AES密钥,默认为新版Xcraft开始开发日期
        'worker_num' => 8,
        'max_request' => 10000
    );
    file_put_contents($setting_file,json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}else{
    $settings=json_decode(file_get_contents($setting_file),true);
    $StartingMessage["YesConfig:settings.json"] = array("加载配置文件: settings.json",0);
    if($settings == null)
        die("FATAL ERROR(0)!\r\n");
}
$settings["Interval"]=round(1000/$settings["TPS"]);
if(!file_exists($module_file)) {
    $StartingMessage["NoConfig:modules.json"] = array("找不到配置文件: modules.json,正在试图创建新文件",1);
    $modules=array(
        "Logger",
        "Security",
        "Network",
        "UserControl",
        "ServerControl",
        "Daemon",
        "ExampleModule"
    );
    file_put_contents($module_file,json_encode($modules,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}else{
    $modules=json_decode(file_get_contents($module_file),true);
    $StartingMessage["YesConfig:modules.json"] = array("加载配置文件: modules.json",0);
    if($modules == null)
        die("FATAL ERROR(0)!\r\n");
}
//开始挨个读取Modules
foreach($modules as $module){
    if(file_exists(MODULEDIR.$module.".php")){
        include(MODULEDIR.$module.".php");
        ${$module} = new $module;
        $StartingMessage["YesModule:".$module] = array("加载模组文件: ".$module,0);
    }else{
        $StartingMessage["NoModule:".$module] = array("找不到模组文件: ".$module.".php,Xcraft将无法加载".$module.",如果".$module."是核心Module,则Xcraft会报错崩溃",5);
    }
}
//检测核心Module是否存在
if(!isset($Logger) or !isset($Security) or !isset($Network) or !isset($Daemon) or !isset($UserControl) or !isset($ServerControl)){
    die("FATAL ERROR(1)!\r\n");
}
//Logger开始运行咯!
$Logger->PrintStartingMessages($StartingMessage,XC_VERSION);
$Logger->PrintLine("Logger配置: ".$Logger->SetMP());
$Logger->PrintLine("Security配置: ".$Security->SetMP("aes-128-cbc",$settings["AESPassword"]));
$Logger->PrintLine("Network配置: ".$Network->SetMP($settings["DaemonIP"],$settings["DaemonPort"],$settings["Interval"],$settings['worker_num'],$settings['max_request'],$Logger,$Security,$Daemon,XC_VERSION));
$Logger->PrintLine("UserControl配置: ".$UserControl->SetMP($Logger,$Security,USERDATADIR,DATADIR));
$Logger->Printline("ServerControl配置: ".$ServerControl->SetMP($Logger,$UserControl,$Security,DATADIR,SERVERDIR));
$Logger->PrintLine("Daemon配置: ". $Daemon->SetMP($Logger,$Security,$settings["DaemonPassword"],$UserControl,$ServerControl,DATADIR));
//加载普通module
foreach($modules as $module){
    if($module != "Logger" and $module != "Daemon" and $module != "Security" and $module != "Network" and $module != "UserControl" and $module != "ServerControl" and isset(${$module})){
        $Logger->PrintLine($module."配置: ".${$module}->SetMP($Logger,$Security,$Network,$Daemon,$UserControl,$settings));
    }
}
//加载一些库[Minecraft.php]
//unset掉一些变量,释放内存
unset($StartingMessage);
unset($setting_file);
unset($module_file);
//网页服务器是最晚开启的
$Network->StartWeb();
