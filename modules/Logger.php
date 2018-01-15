<?php
/**
 * Created by PhpStorm.
 * User: dhdj
 * Date: 1/14/18
 * Time: 4:48 PM
 */
class Logger{
    public function __construct(){

    }
    public function PrintStartingMessages($StartingMessage,$Version){
        $this->PrintLine('\--    --/  /------\  |-|  /--   /-------\   |-------|  |----------|');
        $this->PrintLine(' \ \  / /  / /-----/  |-| / /   / /-----\ |  |-|-----|  |----------|');
        $this->PrintLine('  \ || /  / /         |-|/ /   / /      | |  | |             ||     ');
        $this->PrintLine('  / || \  \ \         |-|-/    \ \      | |  | |-----        ||     ');
        $this->PrintLine(' / /  \ \  \ \-----\  |-|       \ \-----/ |  | |             ||     ');
        $this->PrintLine('/--    \--  \------/  |-|        \-------/ \ |-|             --     ');
        $this->PrintLine('                                                        '.$Version.'');
        foreach($StartingMessage as $SingleMessage){
            $this->PrintLine($SingleMessage,0);
        }
    }
    public function PrintLine($Message,$Level = 0){
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
            default:
                $stype = "INFO";
        }
            echo "[" . date("H:i:s") . " " . $stype . "] " . $Message . "\r\n";
    }
}