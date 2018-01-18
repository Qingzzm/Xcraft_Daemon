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
        switch($Level) {
            case 0:
                $stype = "INFO";
                break;
            case 1:
                $stype = "WARN";
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
                $stype = "INFO";
        }
            echo "[" . date("H:i:s") . " " . $stype . "] " . $Message . "\r\n";
    }
}