<?php
require_once __DIR__ . '/../models/Board.php';

class BoardResource
{
    public static function toArray(Board $board): array
    {
        $boardArray = [];
        for ($i = 0; $i < $board->getHeight(); $i++) {
            for ($j = 0; $j < $board->getLength(); $j++) {
                $square = $board->getSquareAt($i, $j);
                
                if ($square->getIsRevealed() == false){

                    if ($square->getIsFlagged()){
                        $boardArray[$i][$j] = BoardParameters::FLAGGED_SQUARE_DISPLAY_SYMBOL;
                        continue;
                    }

                    $boardArray[$i][$j] = '';
                    continue;
                }

                if ($square->getHasMine()){
                    $boardArray[$i][$j] = BoardParameters::MINE_DISPLAY_SYMBOL;
                    continue;
                }

                $boardArray[$i][$j] = $square->getNeighboringMines();
                continue;
            }
        }
        return [
            "board" => $boardArray,
            "game_status" => !$board->isGameOver(),
            "nonMinedCellsRevealed" => $board->areAllNonMinedCellsRevealed()
        ];
    }
}