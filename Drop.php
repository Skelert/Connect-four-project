<?php
include 'C:\Users\banan\OneDrive\ConnectFourService\play\Board.php';

class Drop extends Board {

    public function __construct($rows = 6, $cols = 6) {
        parent::__construct($rows, $cols);
        $this->_setCurrentPlayer(rand(1, 2));
    }

    public function startGame() {
        $this->_dropPiece();
    }

    protected function _dropPiece() {
        if ($this->_moves >= ($this->getRows() * $this->getColumns())) {
            $this->_showNoWinnerMessage();
            return;
        }

        $_target_col = rand(0, $this->getColumns() - 1);
        $_current_board = $this->_getCurrentBoard();

        for ($row = $this->getRows() - 1; $row >= 0; $row--) {
            if ($_current_board[$row][$_target_col] === -1) {
                $_current_board[$row][$_target_col] = $this->_getCurrentPlayer();
                $this->_moves++;
                $this->_setCurrentBoard($_current_board);
                $this->_printBoard();

                if ($this->_checkForWinner($row, $_target_col)) {
                    $this->_showWinnerMessage();
                    return;
                } else {
                    $this->_togglePlayer();
                    $this->_dropPiece();
                }
                return;
            }
        }

        $this->_dropPiece();
    }

    protected function _togglePlayer() {
        $this->_setCurrentPlayer($this->_getCurrentPlayer() === 1 ? 2 : 1);
    }

    /**
     * Check for winner by validating horizontal and vertical connections
     */
    protected function _checkForWinner($row, $col) {
        return $this->_horizontalCheck($row, $col) || $this->_verticalCheck($row, $col);
    }

    /**
     * Check for a horizontal winning condition
     */
    private function _horizontalCheck($row, $col) {
        $_board_array = $this->_getCurrentBoard();
        $_player = $_board_array[$row][$col];
        $_count = 0;

        // Count towards the left
        for ($i = $col; $i >= 0; $i--) {
            if ($_board_array[$row][$i] !== $_player) {
                break;
            }
            $_count++;
        }

        // Count towards the right
        for ($i = $col + 1; $i < $this->getColumns(); $i++) {
            if ($_board_array[$row][$i] !== $_player) {
                break;
            }
            $_count++;
        }

        return $_count >= 4;
    }

    /**
     * Check for a vertical winning condition
     */
    private function _verticalCheck($row, $col) {
        // If the piece is too close to the top, vertical win is not possible
        if ($row >= $this->getRows() - 3) {
            return false;
        }

        $_board_array = $this->_getCurrentBoard();
        $_player = $_board_array[$row][$col];

        // Check the three rows below
        for ($i = $row + 1; $i <= $row + 3; $i++) {
            if ($_board_array[$i][$col] !== $_player) {
                return false;
            }
        }

        return true;
    }
}

?>