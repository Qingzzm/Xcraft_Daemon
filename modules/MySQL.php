<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 2/5/18
 * Time: 10:23 PM
 */
class MySQL
{
    public function __construct()
	{

    }
	
    public function SetMP($Logger,$Host,$User,$Pass)
	{
        $this->Logger = $Logger;
        $this->Host = $Host;
        $this->User = $User;
        $this->Pass = $Pass;
        return json_encode(array("Host"=>$this->Host,"User"=>$this->User,"Pass"=>$this->Pass));
    }
	
	public function GetServerInfoByID($id)
	{
		
	}
	
    public function NewUser($account,$password)
	{
        /*这里的MySQL操作自己写
        就是添加一个User23333
        如果不能登录或者出现了什么问题,那么这样调用Logger返回:$this->Logger->PrintLine("无法连接至MySQL",5);
        其他UserControl里和ServerControl里的函数都要挨个写进来...调用就不需要管了,但是你可以自己看看
        */

    }
	
	public function GetCurrentServerID()
	{
		
	}
}