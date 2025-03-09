<?php
// index.php


// Define URL for file operations
$baseURL = dirname(dirname(__FILE__)) . "/writable/";

// Define strategies
$strategies = array("Smart" => "Smart", "Random" => "Random");

// Retrieve strategy from GET 
define('STRATEGY', $_GET["strategy"]);

//Game class 
class Game {
    public $response;
    public $pid;
    public $reason;   
}

//Initializes Game State 
function initializeGame() {
    global $strategies;
    $new_Game = new Game();

    if (!array_key_exists(STRATEGY, $strategies)) { 
        $new_Game->response = false;
        $new_Game->reason = "strategy unknown";
    } else {
        $new_Game->response = true;
        $new_Game->pid = uniqid();
    }

    return $new_Game;
}


 //Create an empty game board
 
function createEmptyBoard() {
    return array_fill(0, 6, array_fill(0, 7, 0));
}

//Save game state to File 
function saveGameState($game, $board) {
    global $baseURL;
    if ($game->response == true) {
        $board_Desc = fopen($baseURL . $game->pid . ".txt", "w");
        $desc = json_encode(array(
            "pid" => $game->pid,
            "strategy" => STRATEGY,
            "board" => $board
        ));
        fwrite($board_Desc, $desc);
        fclose($board_Desc);
    }
}

// Main execution
$new_Game = initializeGame();
$smart_Board = createEmptyBoard();

// Output game initialization result as JSON
echo json_encode($new_Game);

// Save the game state if initialization was successful
saveGameState($new_Game, $smart_Board);

