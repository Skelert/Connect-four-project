<?php // index.php
$your_Board= new info();
$your_Board->constructor(7,6, array("Smart","Random"));

class info{

    var $width;
    var $height;
    var $strategies;

    function constructor($WIDTH, $HEIGHT, $STRATRGIES){
        $this->width= $WIDTH;
        $this->height= $HEIGHT;
        $this->strategies= $STRATRGIES;
    }
}

echo json_encode($your_Board);
?>