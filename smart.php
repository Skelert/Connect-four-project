<?php
include 'C:\Users\banan\OneDrive\ConnectFourService\play\Drop.php';

class Computer {
    public static function move($board) {
        // Check win in the next move
        $winMove = self::findWinMove($board, $board->getCurrentPlayer());
        if ($winMove !== null) {
            return $winMove;
        }

        // Check to block opponent's winning move
        $opponentPlayer = $board->getCurrentPlayer() == 1 ? 2 : 1;
        $blockMove = self::findWinMove($board, $opponentPlayer);
        if ($blockMove !== null) {
            return $blockMove;
        }

        // If no winning or blocking move, make a random move
        do {
            $col = rand(0, $board->getColumns() - 1);
            $row = $board->dropPiece($col);
        } while ($row === -1);

        return [$row, $col];
    }

    private static function findWinMove($board, $player) {
        for ($col = 0; $col < $board->getColumns(); $col++) {
            $row = $board->getNextAvailableRow($col);
            if ($row !== -1) {
                // Simulate a piece
                $board->setPiece($row, $col, $player);
                
                if ($board->checkWin($row, $col)) {
                    // Undo the simulated piece
                    $board->setPiece($row, $col, 0);
                    return [$row, $col];
                }
                
                // Undo the simulated move
                $board->setPiece($row, $col, 0);
            }
        }
        return null;
    }
}
?>
