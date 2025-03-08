<?php
define('PID', $_GET["pid"]); // constant
define('SLOT', isset($_GET["move"]) ? $_GET["move"] : ""); // Avoid undefined index error
 // constant

$baseURL= dirname(dirname(__FILE__))."/writable/";
$output= new out();
$output->response=false;
if(PID==""){
    $output->reason="Pid not specified";
    echo json_encode($output);
    exit();
} else if(SLOT==""){
    $output->reason="Move not specified";
    echo json_encode($output);
    exit();
} else if(SLOT>6){
    $output->reason="Invalid slot, ".SLOT;
    echo json_encode($output);
    exit();
}



$file= file_get_contents($baseURL.PID.".txt") or exit(json_encode(array("response"=>false, "reason"=>"Unkown pid")));
$record= json_decode($file);
$board= &$record->board;

response(); //add attributes to $output

$board_Desc = fopen($baseURL.PID.".txt", "w");
$desc = json_encode($record);
fwrite($board_Desc, $desc);
fclose($board_Desc);
echo json_encode($output);

function response(){
    global $output;
    global $record;
    $output->response= makePlay(1, (int)SLOT);
    $output->ack_move= checkWin(1, (int)SLOT);

    
    if($output->ack_move["isWin"]){
        $slot=-1;
    } else if($record->strategy=="Smart"){
        $slot= counterAction();
    } else if($record->strategy=="Random"){
        $slot= randomAction();
    }
    if(!makePlay(2, $slot)){
        $slot= randomAction();
        makePlay(2, $slot);
    }
    $output->move= checkWin(2, $slot);
}

function makePlay($turn, $slot){
    if ($slot == -1) return false;  // Ensure valid slot
    global $board;
    for ($y = count($board)-1; $y >= 0; $y--) {
        if ($board[$y][$slot] == 0) {
            $board[$y][$slot] = $turn;
            return true;
        }
    }
    return false; // Return false if move cannot be made
}


function checkWin($turn, $slot){
    $pieces = array();
    if ($slot != -1) {
        $pieces = check($turn);
    }
    return array(
        "slot" => $slot,
        "isWin" => !empty($pieces), 
        "isDraw" => isDraw($pieces),
        "row" => $pieces
    );
}


function check($turn){
    global $board;
    for($x=0; $x<count($board[0]); $x++){
        for($y=count($board)-1; $y>=0; $y--){
            if($board[$y][$x]==$turn){
                if(checkNeighboor($turn, $x-1, $y-1, "UL", 1, "check")==4){
                    return array($x,$y, $x-1,$y-1, $x-2,$y-2, $x-3,$y-3);
                } else if(checkNeighboor($turn, $x, $y-1, "U", 1, "check")==4){
                    return array($x,$y, $x,$y-1, $x,$y-2, $x,$y-3);
                } else if(checkNeighboor($turn, $x+1, $y-1, "UR", 1, "check")==4){
                    return array($x,$y, $x+1,$y-1, $x+2,$y-2, $x+3,$y-3);
                } else if(checkNeighboor($turn, $x+1, $y, "R", 1, "check")==4){
                    return array($x,$y, $x+1,$y, $x+2,$y, $x+3,$y);
                }
            }
        }
    }
    return array();
}

function checkNeighboor($turn, $x, $y, $dir, $counter, $purpose){
    if($counter==4 || $x==-1 || $y==-1 || $x==7 || $y==6){
        return $counter;
    }
    global $board;

    if(($purpose=="block" || $purpose=="win" ) && $counter==3){
            $turn=0;
    }
    if($board[$y][$x]==$turn){
        if($dir=="UL"){
            return checkNeighboor($turn, $x-1, $y-1, $dir, $counter+1, $purpose);
        } else if($dir=="U"){
            return checkNeighboor($turn, $x, $y-1, $dir, $counter+1, $purpose);
        } else if($dir=="UR"){
            return checkNeighboor($turn, $x+1, $y-1, $dir, $counter+1, $purpose);
        } else if($dir=="R"){
            return checkNeighboor($turn, $x+1, $y, $dir, $counter+1, $purpose);
        } else if($dir=="L"){
            return checkNeighboor($turn, $x-1, $y, $dir, $counter+1, $purpose);
        } else if($dir=="DR"){
            return checkNeighboor($turn, $x+1, $y+1, $dir, $counter+1, $purpose);
        } else if($dir=="DL"){
            return checkNeighboor($turn, $x-1, $y+1, $dir, $counter+1, $purpose);
        }
    }
    return $counter;
}

// Artificial Intelligence Bot
function counterAction(){
    global $board;
    //try to win
    for($x=0; $x<count($board[0]); $x++){
        for($y=count($board)-1; $y>=0; $y--){
            if($board[$y][$x]==2){
                if(checkNeighboor(2, $x-1, $y-1, "UL", 1, "win")==4){
                    if($x>2 && $board[$x-3][$y-2]!=0)    return $x-3;
                }
                if(checkNeighboor(2, $x, $y-1, "U", 1, "win")==4){
                    return $x;
                }
                if(checkNeighboor(2, $x+1, $y-1, "UR", 1, "win")==4){
                    if($x<4 && $board[$x+3][$y-2]!=0)    return $x+3;
                }
                if(checkNeighboor(2, $x+1, $y, "R", 1, "win")==4){
                    if($x<4){
                        if($y<count($board)-1 && $board[$x+3][$y+1]==0){
                            continue;
                        }else{
                            return $x+3;
                        }
                    }
                }
                // Check inclomplete from bellow
                if(checkNeighboor(2, $x+1, $y+1, "DR", 1, "win")==4){
                    if($x<4){
                        if($y<2 && $board[$x+3][$y+4]==0){
                            continue;
                        }else{
                            return $x+3;
                        }
                    }
                }
                if(checkNeighboor(2, $x-1, $y+1, "DL", 1, "win")==4){
                    if($x>2){
                        if($y<2 && $board[$x-3][$y+4]==0){
                            continue;
                        }else{
                            return $x-3;
                        }
                    }
                }
            }
        }
    }
    //block opponent
    for($x=0; $x<count($board[0]); $x++){
        for($y=count($board)-1; $y>=0; $y--){
            if($board[$y][$x]==1){
                if(checkNeighboor(1, $x-1, $y-1, "UL", 1, "block")==4){
                    if($x>2 && $board[$x-3][$y-2]!=0)    return $x-3;
                }
                if(checkNeighboor(1, $x, $y-1, "U", 1, "block")==4){
                    return $x;
                }
                if(checkNeighboor(1, $x+1, $y-1, "UR", 1, "block")==4){
                    if($x<4 && $board[$x+3][$y-2]!=0)    return $x+3;
                }
                if(checkNeighboor(1, $x+1, $y, "R", 1, "block")==4){
                    if($x<4){
                        if($y<count($board)-1 && $board[$x+3][$y+1]==0){
                            continue;
                        }else{
                            return $x+3;
                        }
                    }
                }
                if(checkNeighboor(1, $x-1, $y, "L", 1, "block")==4){
                    if($x>2){
                        if($y<count($board)-1 && $board[$x-3][$y+1]==0){
                            continue;
                        }else{
                            return $x-3;
                        }
                    }
                }
                if(checkNeighboor(1, $x+1, $y+1, "DR", 1, "block")==4){
                    if($x<4){
                        if($y<2 && $board[$x+3][$y+4]==0){
                            continue;
                        }else{
                            return $x+3;
                        }
                    }
                }
                if(checkNeighboor(1, $x-1, $y+1, "DL", 1, "block")==4){
                    if($x>2){
                        if($y<2 && $board[$x-3][$y+4]==0){
                            continue;
                        }else{
                            return $x-3;
                        }
                    }
                }
            }
        }
    }
    
    for($x=0; $x<count($board[0]); $x++){
        for($y=count($board)-1; $y>=0; $y--){
            if($board[$y][$x]==2){

                $paths=array("UL"=> checkNeighboor(2, $x-1, $y-1, "UL", 1, "win"),
                    "U"=> checkNeighboor(2, $x, $y-1, "U", 1, "win"),
                    "UR"=> checkNeighboor(2, $x+1, $y-1, "UR", 1, "win"),
                    "R"=> checkNeighboor(2, $x+1, $y, "R", 1, "win"),
                    "L"=> checkNeighboor(2, $x-1, $y, "L", 1, "win"),
                    "DR"=> checkNeighboor(2, $x+1, $y+1, "DR", 1, "win"),
                    "DL"=> checkNeighboor(2, $x-1, $y+1, "DL", 1, "win")
                );
                $longest= max($paths);
                if($paths["UL"]==$longest){
                    if($x>2 && $board[$x-$longest+1][$y-$longest+2]!=0)    return $x-3;
                }
                if($paths["U"]==$longest){
                    return $x;
                }
                if($paths["UR"]==$longest){
                    if($x<4 && $board[$x+$longest-1][$y-$longest+2]!=0)    return $x+3;
                }
                if($paths["R"]==$longest){
                    if($x<4){
                        if($y<count($board)-1 && $board[$x+$longest-1][$y+$longest-2]==0){
                            continue;
                        }else{
                            return $x+3;
                        }
                    }
                }
                if($paths["L"]==$longest){
                    if($x>2){
                        if($y<count($board)-1 && $board[$x-$longest+1][$y+$longest-2]==0){
                            continue;
                        }else{
                            return $x-3;
                        }
                    }
                }
                if($paths["DR"]==$longest){
                    if($x<4){
                        if($y<2 && $board[$x+3][$y+4]==0){
                            continue;
                        }else{
                            return $x+3;
                        }
                    }
                }
                if($paths["DL"]==$longest){
                    if($x>2){
                        if($y<2 && $board[$x-3][$y+4]==0){
                            continue;
                        }else{
                            return $x-3;
                        }
                    }
                }
            }
        }
    }
    return randomAction();
}

function randomAction(){
    $x= rand(0,6);
    global $board;
    while($board[0][$x]!=0){
        $x= rand(0,6);
    }

    return $x;
}

function isDraw($pieces){
    if(empty($pieces)){
        return false;
    }
    global $board;
    for($x=0; $x<count($board[0]); $x++){
        for($y=count($board)-1; $y>=0; $y--){
            if($board[$y][$x]==0){
                return false;
            }
        }
    }
    return true;
}

class out {
    public $response;
    public $reason;
    public $ack_move;
    public $move;
}

?>