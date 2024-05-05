<?php
require_once __DIR__ . '/Square.php';
require_once __DIR__ . '/Board.php';

$board = new Board(8, 8, 15);
//TODO fix infinite loop on different height / length values
$board->display();