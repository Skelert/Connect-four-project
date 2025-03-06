<?php
include 'C:\Users\banan\OneDrive\ConnectFourService\play\Board.php';

class Drop extends Board {

    public function __construct($rows = 6, $cols = 6) {
        parent::__construct($rows, $cols);
    }

    public function dropPiece($col) {
        $currentBoard = $this->_getCurrentBoard();
    
        if ($col < 0 || $col >= $this->getColumns()) {
            echo "<p>Invalid column selection!</p>";
            return -1;
        }
    
        for ($row = $this->getRows() - 1; $row >= 0; $row--) {
            if ($currentBoard[$row][$col] === -1) {
                $currentBoard[$row][$col] = $this->_getCurrentPlayer();
                $this->_setCurrentBoard($currentBoard);
                $_SESSION['game_board'] = $currentBoard; // Save board state
                $_SESSION['current_player'] = $this->_getCurrentPlayer(); // Save turn
                $this->_moves++;
                return $row;
            }
        }
    
        echo "<p>Column $col is full. Pick another column.</p>";
        return -1;
    }
    
    

    public function checkForWinner($row, $col) {
        return $this->_horizontalCheck($row, $col) ||
               $this->_verticalCheck($row, $col) ||
               $this->_diagonalCheck($row, $col);
    }

    protected function _horizontalCheck($row, $col) {
        $grid = $this->_getCurrentBoard();
        $player = $grid[$row][$col];
        $count = 1;

        for ($i = $col - 1; $i >= 0 && $grid[$row][$i] === $player; $i--) $count++;
        for ($i = $col + 1; $i < $this->getColumns() && $grid[$row][$i] === $player; $i++) $count++;

        return $count >= 4;
    }

    protected function _verticalCheck($row, $col) {
        $grid = $this->_getCurrentBoard();
        $player = $grid[$row][$col];

        if ($row > $this->getRows() - 4) return false;

        return ($grid[$row+1][$col] === $player &&
                $grid[$row+2][$col] === $player &&
                $grid[$row+3][$col] === $player);
    }

    protected function _diagonalCheck($row, $col) {
        return $this->_diagonalCheckLeft($row, $col) || 
               $this->_diagonalCheckRight($row, $col);
    }

    protected function _diagonalCheckLeft($row, $col) {
        $grid = $this->_getCurrentBoard();
        $player = $grid[$row][$col];

        $count = 1;
        for ($i = 1; $row - $i >= 0 && $col - $i >= 0 && $grid[$row - $i][$col - $i] === $player; $i++) $count++;
        for ($i = 1; $row + $i < $this->getRows() && $col + $i < $this->getColumns() && $grid[$row + $i][$col + $i] === $player; $i++) $count++;

        return $count >= 4;
    }

    protected function _diagonalCheckRight($row, $col) {
        $grid = $this->_getCurrentBoard();
        $player = $grid[$row][$col];

        $count = 1;
        for ($i = 1; $row - $i >= 0 && $col + $i < $this->getColumns() && $grid[$row - $i][$col + $i] === $player; $i++) $count++;
        for ($i = 1; $row + $i < $this->getRows() && $col - $i >= 0 && $grid[$row + $i][$col - $i] === $player; $i++) $count++;

        return $count >= 4;
    }
    public function togglePlayer() {
        $this->_setCurrentPlayer($this->_getCurrentPlayer() === 1 ? 2 : 1);
    }
    
}
?>
