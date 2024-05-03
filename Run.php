<?php
require_once __DIR__ . '/Square.php';
require_once __DIR__ . '/Board.php';

$board = new Board(8, 8, 15);
// echo 'after all';
// echo PHP_EOL;
// echo $board->getHeight();
// echo PHP_EOL;
// echo $board->getLength();
// echo PHP_EOL;
// echo $board->getMinesNum();
// echo PHP_EOL;
$board->display();