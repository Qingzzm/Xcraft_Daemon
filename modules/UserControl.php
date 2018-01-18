<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/18/18
 * Time: 6:50 AM
 */
class UserControl{
    public function __construct(){

    }
    public function SetMP($Logger,$DIR){
        $this->Logger = $Logger;
        $this->DIR = $DIR;
        return json_encode(array("USERDATADIR"=>$DIR));
    }
}