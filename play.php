<?php
// Define constants for player ID and move
define('PID', $_GET["pid"]);
define('SLOT', isset($_GET["move"]) ? $_GET["move"] : "");

// Set the base URL for File
$baseURL = dirname(dirname(__FILE__))."/writable/";
$output = new out();
$output->response = false;

// Validate input 
if(PID == "") {
    $output->reason = "Pid not specified";
    echo json_encode($output);
    exit();
} else if(SLOT == "") {
    $output->reason = "Move not specified";
    echo json_encode($output);
    exit();
} else if(SLOT > 6) {
    $output->reason = "Invalid slot, ".SLOT;
    echo json_encode($output);
    exit();
}

// Load the game state from file
$file = file_get_contents($baseURL.PID.".txt") or exit(json_encode(array("response" => false, "reason" => "Unknown pid")));
$record = json_decode($file);
$board = &$record->board;

// Process the game turn
response(); // Add attributes to $output

// Save the updated game state
$board_Desc = fopen($baseURL.PID.".txt", "w");
$desc = json_encode($record);
fwrite($board_Desc, $desc);
fclose($board_Desc);
echo json_encode($output);


 //Process the game turn and update the output

function response() {
    global $output, $record;
    $output->response = makePlay(1, (int)SLOT);
    $output->ack_move = checkWin(1, (int)SLOT);

    // Determine AI move based on strategy
    if($output->ack_move["isWin"]) {
        $slot = -1;
    } else if($record->strategy == "Smart") {
        $slot = counterAction();
    } else if($record->strategy == "Random") {
        $slot = randomAction();
    }
    if(!makePlay(2, $slot)) {
        $slot = randomAction();
        makePlay(2, $slot);
    }
    $output->move = checkWin(2, $slot);
}


 //Make a play on the board
 //  int $turn The current player's turn
 //  int $slot The slot to play in
 //returns a bool Whether the play was successful
 
function makePlay($turn, $slot) {
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

/**
 * Check if the current move results in a win
 * @param int $turn The current player's turn
 * @param int $slot The slot that was played
 * @return array The result of the move
 */
function checkWin($turn, $slot) {
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


 // Check for a winning combination on the board
 //@param int $turn The current player's turn
 //@return array The winning combination if found, empty array otherwise
 
function check($turn) {
    global $board;
    for($x = 0; $x < count($board[0]); $x++) {
        for($y = count($board)-1; $y >= 0; $y--) {
            if($board[$y][$x] == $turn) {
                // Check for winning combinations in different directions
                if(checkAdjacent($turn, $x-1, $y-1, "UL", 1, "check") == 4) {
                    return array($x, $y, $x-1, $y-1, $x-2, $y-2, $x-3, $y-3);
                } else if(checkAdjacent($turn, $x, $y-1, "U", 1, "check") == 4) {
                    return array($x, $y, $x, $y-1, $x, $y-2, $x, $y-3);
                } else if(checkAdjacent($turn, $x+1, $y-1, "UR", 1, "check") == 4) {
                    return array($x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3);
                } else if(checkAdjacent($turn, $x+1, $y, "R", 1, "check") == 4) {
                    return array($x, $y, $x+1, $y, $x+2, $y, $x+3, $y);
                }
            }
        }
    }
    return array();
}


 // Check adjacent cells for a win move
 
function checkAdjacent($turn, $x, $y, $dir, $counter, $purpose) {
    global $board;
    $width = count($board[0]);
    $height = count($board);
    
    if ($counter == 4) {
        return $counter;
    }
    
    // Apply wraparound for horizontal and vertical directions
    if ($x < 0) {
        $x = $width - 1; // Wrap around to the right
    } else if ($x >= $width) {
        $x = 0; // Wrap around to the left
    }
    if ($y < 0) {
        $y = $height - 1; // Wrap around to the bottom
    } else if ($y >= $height) {
        $y = 0; // Wrap around to the top
    }
    
    if ($board[$y][$x] == $turn) {
        if ($dir == "R") {
            return checkAdjacent($turn, $x + 1, $y, $dir, $counter + 1, $purpose);
        } else if ($dir == "L") {
            return checkAdjacent($turn, $x - 1, $y, $dir, $counter + 1, $purpose);
        } else if ($dir == "U") {
            return checkAdjacent($turn, $x, $y - 1, $dir, $counter + 1, $purpose);
        } else if ($dir == "D") {
            return checkAdjacent($turn, $x, $y + 1, $dir, $counter + 1, $purpose);
        }
    }
    return $counter;
}


 //Implement AI strategy for counter moves
 
function counterAction() {
    global $board;
    // Try to win
    for($x = 0; $x < count($board[0]); $x++) {
        for($y = count($board)-1; $y >= 0; $y--) {
            if($board[$y][$x] == 2) {
               
               
            }
        }
    }
    // Block opponent
    for($x = 0; $x < count($board[0]); $x++) {
        for($y = count($board)-1; $y >= 0; $y--) {
            if($board[$y][$x] == 1) {
               
                
            }
        }
    }
    
    // Make strategic moves
    for($x = 0; $x < count($board[0]); $x++) {
        for($y = count($board)-1; $y >= 0; $y--) {
            if($board[$y][$x] == 2) {
                
               
            }
        }
    }
    return randomAction();
}


 // Generate a random valid move
 
function randomAction() {
    $x = rand(0, 6);
    global $board;
    while($board[0][$x] != 0) {
        $x = rand(0, 6);
    }
    return $x;
}


 // Check if the game is a draw
 // @param array $pieces The winning combination (if any)
 //@return bool Whether the game is a draw
 
function isDraw($pieces) {
    if(empty($pieces)) {
        return false;
    }
    global $board;
    for($x = 0; $x < count($board[0]); $x++) {
        for($y = count($board)-1; $y >= 0; $y--) {
            if($board[$y][$x] == 0) {
                return false;
            }
        }
    }
    return true;
}


 // Class to store the game output
 
class out {
    public $response;
    public $reason;
    public $ack_move;
    public $move;
}

?>
