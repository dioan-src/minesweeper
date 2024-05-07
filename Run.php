<?php
require_once __DIR__ . '/Square.php';
require_once __DIR__ . '/Board.php';

//run preset board for testing
// $presetBoard = include 'preset1.php';
// $presetBoard = include 'preset2.php';
// $board = new Board(presetGrid:$presetBoard);

//run randomized board
$board = new Board(length:16, height:16);

$board->display();