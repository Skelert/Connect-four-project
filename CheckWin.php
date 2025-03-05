<?php
include 'C:\Users\banan\OneDrive\ConnectFourService\play\Drop.php';

class Message extends Drop {

    public function __construct($rows = 6, $cols = 6) {
        parent::__construct($rows, $cols);
    }

    protected function _printBoard() {
        print '<p>Player ' . $this->_getCurrentPlayer() . ': Move No. ' . $this->_moves . '</p>';
        print '<table border="1" cellspacing="0" cellpadding="5">';

        $_board_array = $this->_getCurrentBoard();

        for ($i = 0; $i < $this->getRows(); $i++) {
            print '<tr>';
            for ($j = 0; $j < $this->getColumns(); $j++) {
                $_class = "";
                if ($_board_array[$i][$j] === 1) {
                    $_class = "player-1";
                } else if ($_board_array[$i][$j] === 2) {
                    $_class = "player-2";
                }
                print '<td class="' . $_class . '">' . ($_board_array[$i][$j] === -1 ? "." : $_board_array[$i][$j]) . '</td>';
            }
            print '</tr>';
        }
        print '</table>';
    }

    protected function _showWinnerMessage() {
        print '<p class="message">Player ' . $this->_getCurrentPlayer() . ' wins the game!</p>';
    }

    protected function _showNoWinnerMessage() {
        print '<p class="message">No winner for this round.</p>';
    }
}


?>
