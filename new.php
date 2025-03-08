<?php // index.php
//$baseURL = "C:/wamp/www/Programming_Languages_PR1/C4service/writable/";
$baseURL= dirname(dirname(__FILE__))."/writable/";

define('STRATEGY', $_GET["strategy"]); // constant
$strategies = array("Smart"=>"Smart", "Random"=>"Random"); // supported strategies
$new_Game= new Game();

if (!array_key_exists(STRATEGY, $strategies)) { 
    $new_Game->response = false;
    $new_Game->reason = "strategy unkown";
} else{
    $new_Game->response = true;
    $new_Game->pid = uniqid();
}

$out= json_encode($new_Game);
echo $out;

$smart_Board= array(
    array(0,0,0,0,0,0,0),
    array(0,0,0,0,0,0,0),
    array(0,0,0,0,0,0,0),
    array(0,0,0,0,0,0,0),
    array(0,0,0,0,0,0,0),
    array(0,0,0,0,0,0,0)
);

if($new_Game->response==true){
    $board_Desc = fopen($baseURL.$new_Game->pid.".txt", "w");
    $desc = json_encode(array("pid"=>$new_Game->pid,"strategy"=>STRATEGY, "board"=>$smart_Board));
    fwrite($board_Desc, $desc);
    fclose($board_Desc);
}

//class to store necessary varaibles to be added
class Game{
    public $response;
    public $pid;
    public $reason;   
}
?>

