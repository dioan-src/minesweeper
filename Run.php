<?php
require_once __DIR__ . '/models/Board.php';
require_once __DIR__ . '/handlers/MoveHandler.php';

//get first square reveal from user
list($row, $column) = MoveHandler::getStart();

//create randomized board
$board = new Board(length:16, height:16, seedHeight:($row-1), seedLength:($column-1));
$board->display();
MoveHandler::handleMove('o-' . $row . '-' . $column, $board);
$board->display();
while ( $board->getIsGameOver() == false ) {
    MoveHandler::handleMove( MoveHandler::getMove(), $board );
    $board->display();
}

echo ($board->areAllNonMinedCellsRevealed())?'You won motherfucker':'Lol. Another loss for you';