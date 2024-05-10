<?php
require_once __DIR__ . '/models/Board.php';
require_once __DIR__ . '/handlers/MoveHandler.php';

//run preset board for testing
$presetBoard = include 'data/preset8x8.php';
// $presetBoard = include 'data/preset16x16.php';
// $presetBoard = include 'data/preset30x16.php';

$board = new Board(presetGrid:$presetBoard);


$board->display();
while ( $board->getIsGameOver() == false ) {
    MoveHandler::handleMove( MoveHandler::getMove(), $board );
    $board->display();
}

echo ($board->areAllNonMinedCellsRevealed())?'You won motherfucker':'Lol. Another loss for you';