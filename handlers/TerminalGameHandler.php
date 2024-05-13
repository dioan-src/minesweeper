<?php
require_once __DIR__ . '/../models/Board.php';
require_once __DIR__ . '/../utils/BoardParameters.php';
require_once __DIR__ . '/../handlers/MoveHandler.php';

class TerminalGameHandler{    
    
    public static function playGameWithRandomizedBoard(): void
    {
        list($row, $column) = MoveHandler::getStart();
        $board = new Board(length:16, height:16, seedHeight:($row-1), seedLength:($column-1));
        MoveHandler::handleMove('o-' . $row . '-' . $column, $board);
        self::displayBoard($board);

        while (!$board->getIsGameOver()) {
            MoveHandler::handleMove(MoveHandler::getMove(), $board);
            self::displayBoard($board);
        }

        if ($board->areAllNonMinedCellsRevealed()){
            self::winnerEcho();
        }else{
            self::loserEcho();
        }
    }

    public static function playWithPresetBoard(array $presetBoard): void
    {
        $board = new Board(presetGrid:$presetBoard);


        self::displayBoard($board);
        while ( $board->getIsGameOver() == false ) {
            MoveHandler::handleMove( MoveHandler::getMove(), $board );
            self::displayBoard($board);
        }

        if ($board->areAllNonMinedCellsRevealed()){
            self::winnerEcho();
        }else{
            self::loserEcho();
        }
    }

    public static function displayBoard(Board $board): void
    {
        // Print column numbers
        echo "  ";
        for ($j = 0; $j < $board->getLength(); $j++) {
            self::formatCellOutput($j + 1);
        }
        echo PHP_EOL;
        echo "  ";
        for ($j = 0; $j < $board->getLength(); $j++) {
            self::formatCellOutput('_');
        }
        echo PHP_EOL;

        // Print board with row numbers and cells
        for ($i = 0; $i < $board->getHeight(); $i++) {
            // Print row number
            self::formatCellOutput(output:$i + 1,  suffix:'|');

            // Print cells
            for ($j = 0; $j < $board->getLength(); $j++) {
                $square = $board->getSquareAt($i, $j);
                
                //remove padding for the first column of cells
                $positionPadflag = true;
                
                if ($square->getIsRevealed() == false){

                    if ($square->getIsFlagged()){
                        self::formatCellOutput(output:BoardParameters::FLAGGED_SQUARE_DISPLAY_SYMBOL, pad:false, suffix:'  ');
                        continue;
                    }

                    self::formatCellOutput(output:BoardParameters::HIDDEN_SQUARE_DISPLAY_SYMBOL, pad:true, suffix:'  ');
                    continue;
                }

                if ($square->getHasMine()){
                    self::formatCellOutput(output:BoardParameters::MINE_DISPLAY_SYMBOL, pad:false, suffix:'  ');
                    continue;
                }

                $val = $square->getNeighboringMines() != 0 ? $square->getNeighboringMines() : ' ';
                self::formatCellOutput(output:$val, pad:false, suffix:'  ');
                continue;
            }
            echo '|' . PHP_EOL;
        }
    }

    public static function formatCellOutput($output, $pad = true, $suffix = ' '): void
    {
        if ($pad) $output = str_pad($output, 2, ' ', STR_PAD_LEFT);
        echo $output . $suffix;
    }

    public static function winnerEcho() : void {
        echo 'Wow! Such Win! Very Success!';
    }

    public static function loserEcho() : void {
        echo 'Lol. Another loss for you';
    }
    
}