<?php
// index.php 

 
class info {
  
    var $width;
    var $height;
    var $strategies;

    
     //Constructor for the info class
    
    function constructor($WIDTH, $HEIGHT, $STRATEGIES) {
        $this->width = $WIDTH;
        $this->height = $HEIGHT;
        $this->strategies = $STRATEGIES;
    }
}

// Create instance of the game board 
$your_Board = new info();

// Initialize the game board with dimensions 7x6 and AI strategies
$your_Board->constructor(7, 6, array("Smart", "Random"));

// Output the game board configuration as JSON
echo json_encode($your_Board);
?>
