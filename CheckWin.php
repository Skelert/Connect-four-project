<?php
include 'C:\Users\banan\OneDrive\ConnectFourService\play\Drop.php';

class Message extends Drop {

    public function __construct($rows = 6, $cols = 6) {
        parent::__construct($rows, $cols);
    }

    public function startGame() {
        if (!isset($_SESSION['game_board'])) {
            echo "<p>Initializing new game...</p>";
            $this->_initializeGameBoard(); // Reset board only on the first run
            $this->_setCurrentPlayer(1); // Player 1 starts
            $this->_moves = 0;
    
            $_SESSION['game_board'] = $this->_getCurrentBoard();
            $_SESSION['current_player'] = $this->_getCurrentPlayer();
        } else {
            echo "<p>Loading existing game...</p>";
            $this->_setCurrentBoard($_SESSION['game_board']);
            $this->_setCurrentPlayer($_SESSION['current_player']);
        }
    
        $this->printBoard(); // Ensure board is displayed
    }
    
    

    public function printBoard() {
        echo '<p>Player ' . $this->_getCurrentPlayer() . ': Move No. ' . $this->_moves . '</p>';
    
        if (isset($_GET['col']) && !isset($_SESSION['last_move_processed'])) {
            $_SESSION['last_move_processed'] = true;
            $this->processMove((int)$_GET['col']);
        }
    
        echo '<form method="GET" action="index.php">';
        echo '<table border="1" cellspacing="0" cellpadding="5">';
    
        echo '<tr>';
        for ($j = 0; $j < $this->getColumns(); $j++) {
            echo '<th><button type="submit" name="col" value="' . $j . '">↓</button></th>';
        }
        echo '</tr>';
    
        $board = $_SESSION['game_board'] ?? $this->_getCurrentBoard();
    
        for ($i = 0; $i < $this->getRows(); $i++) {
            echo '<tr>';
            for ($j = 0; $j < $this->getColumns(); $j++) {
                $class = "";
                if ($board[$i][$j] === 1) {
                    $class = "player-1";
                } else if ($board[$i][$j] === 2) {
                    $class = "player-2";
                }
                echo '<td class="' . $class . '">' . ($board[$i][$j] === -1 ? "." : $board[$i][$j]) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</form>';
    
        // Allow next move
        unset($_SESSION['last_move_processed']);
    }
    
    
    

    public function showWinnerMessage($player) {
        echo '<p class="message">Player ' . $player . ' wins the game!</p>';
    }

    public function showNoWinnerMessage() {
        echo '<p class="message">No winner for this round.</p>';
    }
    public function processMove($col) {
        echo "<p>Processing move for column: $col</p>";
    
        // Drop the piece for the player
        $row = $this->dropPiece($col);
        if ($row === -1) return;
    
        if ($this->checkForWinner($row, $col)) {
            echo "<p>Player " . $this->_getCurrentPlayer() . " wins!</p>";
            session_destroy();
            return;
        }
    
        $this->togglePlayer();
    
        // Computer move
        do {
            $compCol = rand(0, $this->getColumns() - 1);
            $compRow = $this->dropPiece($compCol);
        } while ($compRow === -1);
    
        if ($this->checkForWinner($compRow, $compCol)) {
            echo "<p>Computer wins!</p>";
            session_destroy();
            return;
        }
    
        $this->togglePlayer();
    
        // **Ensure board is saved**
        $_SESSION['game_board'] = $this->_getCurrentBoard();
        $_SESSION['current_player'] = $this->_getCurrentPlayer();
    
        $this->printBoard(); // Refresh board
    }
    
}
?>
