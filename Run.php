<?php
require_once __DIR__ . '/Square.php';
require_once __DIR__ . '/Board.php';

$board = new Board(16, 16);
//TODO fix infinite loop on different height / length values
$board->display();