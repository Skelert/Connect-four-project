<?php
include 'C:\Users\banan\OneDrive\ConnectFourService\play\Drop.php';

class Computer {
    public static function move($board) {
        do {
            $col = rand(0, $board->getColumns() - 1);
            $row = $board->dropPiece($col); // Drop piece for computer
        } while ($row === -1); // Retry if column was full

        return [$row, $col]; // Return position where piece was dropped
    }
}
?>
