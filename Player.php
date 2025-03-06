<?php
include 'C:\Users\banan\OneDrive\ConnectFourService\play\Drop.php';
include 'C:\Users\banan\OneDrive\ConnectFourService\new\random.php';

class Player {
    public static function move($board, $col) {
        $row = $board->dropPiece($col); // Drop piece for player

        if ($row === -1) {
            return false; // Column full, invalid move
        }

        // Check if player wins
        if ($board->checkForWinner($row, $col)) {
            echo "<p><b>Player wins!</b></p>";
            session_destroy(); // End game
            exit;
        }

        // Switch turn to computer
        $board->togglePlayer();
        list($compRow, $compCol) = Computer::move($board);

        // Check if computer wins
        if ($board->checkForWinner($compRow, $compCol)) {
            echo "<p><b>Computer wins!</b></p>";
            session_destroy(); // End game
            exit;
        }

        // Switch turn back to player
        $board->togglePlayer();
        return true;
    }
}
?>