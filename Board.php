<?php

include 'C:\Users\banan\OneDrive\ConnectFourService\info\info.php';

class Board extends Hi {

    public function __construct($rows = 6, $cols = 6) {
        parent::__construct($rows, $cols);
        $this->_initializeGameBoard();
    }

    protected function _initializeGameBoard() {
        $_board_array = array();
        for ($i = 0; $i < $this->getRows(); $i++) {
            $_board_array[$i] = array();
            for ($j = 0; $j < $this->getColumns(); $j++) {
                $_board_array[$i][$j] = -1;
            }
        }
        $this->_setCurrentBoard($_board_array);
    }
}

?>