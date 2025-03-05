<?php
class Hi {
    protected $_rows = 6;
    protected $_columns = 6;
    protected $_board_array = array();
    protected $_current_player = 0;
    protected $_moves = 0;

    function __construct($rows = 6, $cols = 6) {
        $this->_setDimensions($rows, $cols);
    }

    protected function _setDimensions($rows = 6, $cols = 6) {
        $this->_rows = $rows;
        $this->_columns = $cols;
    }

    public function getRows() {
        return $this->_rows;
    }

    public function getColumns() {
        return $this->_columns;
    }

    protected function _getCurrentPlayer() {
        return $this->_current_player;
    }

    protected function _setCurrentPlayer($player_no) {
        $this->_current_player = $player_no;
    }

    protected function _getCurrentBoard() {
        return $this->_board_array;
    }

    protected function _setCurrentBoard($board_array) {
        $this->_board_array = $board_array;
    }
}

?>