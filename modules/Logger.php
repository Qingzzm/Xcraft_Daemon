<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/14/18
 * Time: 4:48 PM
 */
class Logger{
    /**
     * Logger constructor.
     */
    public function __construct(){

    }

    /**
     * @return string
     */
    public function setMP(){
        return json_encode(array());
    }

    /**
     * @param $StartingMessage
     * @param $Version
     */
    public function PrintStartingMessages($StartingMessage, $Version){
        $this->PrintLine('\--    --/  /------\  |-|  /--   /-------\   |-------|  |----------|');
        $this->PrintLine(' \ \  / /  / /-----/  |-| / /   / /-----\ |  |-|-----|  |----------|');
        $this->PrintLine('  \ || /  / /         |-|/ /   / /      | |  | |             ||     ');
        $this->PrintLine('  / || \  \ \         |-|-/    \ \      | |  | |-----        ||     ');
        $this->PrintLine(' / /  \ \  \ \-----\  |-|       \ \-----/ |  | |             ||     ');
        $this->PrintLine('/--    \--  \------/  |-|        \-------/ \ |-|             --     ');
        $this->PrintLine('                                                        '.$Version.'');
        foreach($StartingMessage as $SingleMessage){
            $this->PrintLine($SingleMessage[0],$SingleMessage[1]);
        }
    }

    /**
     * @param $Message
     * @param int $Level
     */
    public function PrintLine($Message, $Level = 0){
        if(preg_match('/WIN*/',PHP_OS)){
            echo "[" . date("H:i:s") . " " . $this->GetLevel($Level) . "] " . $Message . "\r\n";
           } else {
	          	$c="32";       
            if($Level>0){
            	$c="33";
            }
            if($Level>1){
            	$c="31";
            }
            if($Level>4){
            	$c='40;31';
            }
            $Message=str_replace('"',"'",$Message);
            if($Level>4){
                system('echo -e "\033[31m ------------------------------------------------- \033[0m"');
            }
            system('echo -e "\033['.$c.'m '."[" . date("H:i:s") . " " . $this->GetLevel($Level) . "] " .$Message.' \033[0m\r\n"');
            if($Level>4){
                system('echo -e "\033[31m ------------------------------------------------- \033[0m"');
            }
            unset($c);
        if($this->GetLevel($Level) == "FATAL") {
	          die("THE DAEMON DIES BECAUSE AN FATAL ERROR OCCURRED\r\n");
            }
          }
        }

    /**
     * @param $Message
     * @param int $Level
     * @param bool $status
     * @return string
     */
    public function PrintJson($Message, $Level = 0, $status = true){
        return json_encode(array("Message"=>'['.$this->GetLevel($Level).']'.$Message,"Status"=>$status));
    }

    /**
     * @param $Level
     * @return string
     */
    public function GetLevel($Level){
        switch($Level) {
            case 0:
                $stype = "INFORM";
                break;
            case 1:
                $stype = "WARNING";
                break;
            case 2:
                $stype = "ERROR";
                break;
            case 4:
                $stype = "DANGER";
                break;
            case 5:
                $stype = "PANIC";
                break;
            case 6:
                $stype = "DEADLY";
                break;
            case 7:
                $stype = "FATAL";
                break;
            default:
                $stype = "INFORM";
        }
        return $stype;
    }
}