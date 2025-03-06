<?php
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include 'C:\Users\banan\OneDrive\ConnectFourService\play\CheckWin.php';

$game = new Message(6, 6);
$game->startGame();

?>
