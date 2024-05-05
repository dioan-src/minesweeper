<?php
require_once __DIR__ . '/Square.php';

// class for  the minsweeper board
class Board
{
    private int $length;
    private int $height;
    private int $minesNum;    
    private array $board;
    const MINES_PERCENTAGE = 0.18;
    const SUB_BOARD_HEIGHT = 3;
    const SUB_BOARD_LENGTH = 3;
    const IS_HIDDEN = 0;
    const SHOW_MINE = '*';
    const SHOW_HIDDEN_SQUARE = '\u{25A0}';

    public function __construct(int $length, int $height)
    {
        $this->length = $length;
        $this->height = $height;
        $this->minesNum = (int)$length*$height*self::MINES_PERCENTAGE;
        $this->initializeBoard();
    }

    // Setter for $length
    public function setLength(int $length): void {
        $this->length = $length;
    }

    // Getter for $length
    public function getLength(): int {
        return $this->length;
    }

    // Setter for $height
    public function setHeight(int $height): void {
        $this->height = $height;
    }

    // Getter for $height
    public function getHeight(): int {
        return $this->height;
    }

    // Setter for $minesNum
    public function setMinesNum(int $minesNum): void {
        $this->minesNum = $minesNum;
    }

    // Getter for $minesNum
    public function getMinesNum(): int {
        return $this->minesNum;
    }

    // Setter for $minesNum
    public function setBoard(array $board): void {
        $this->board = $board;
    }

    // Getter for $minesNum
    public function getBoard(): array {
        return $this->board;
    }

    public function initializeBoard()
    {
        $boardWithMines = $this->createBoardWithRandomizedMines();

        //TODO - CHANGE THIS - I DONT WANT TO SET A BOARD AND THEN RESET IT.
        $this->setBoard($boardWithMines);

        $boardMadeOfSquares = $this->initializeSquaresOfBoard($boardWithMines);
         
        $this->setBoard($boardMadeOfSquares);
    }

    public function createBoardWithRandomizedMines(): array
    {
        //create mines array with $this->minesNum elements, based on the constructor of Board
        $minesArray = array_fill(0, ($this->minesNum), 1);
        //create non-mines array with x elements, based on the constructor of Board
        $nonMinesArray = array_fill(0, ($this->height*$this->length-$this->minesNum), 0);
        //merge arrays and shuffle to get the final array
        $finalArray = array_merge($minesArray, $nonMinesArray);
        shuffle($finalArray);
        //chunk the array based on the constructor height, to create the final board
        return array_chunk($finalArray, $this->length);
    }

    public function initializeSquaresOfBoard(array $boardValues): array
    {
        $boardOfSquares = [];
        for ($i = 0; $i < $this->height; $i++) {

            for ($j = 0; $j < $this->length; $j++) {
                $boardOfSquares[$i][$j] = new Square( $this->getAdjacentMines($i, $j), $j, $i, (bool)$boardValues[$i][$j], false, false);
            }
            
        }

        return $boardOfSquares;
    }

    public function getAdjacentMines(int $centerHeight, int $centerLength): int
    {
        $adjacentMines = 0;
        
        $startHeight = $centerHeight - 1;                   //set start height of outer loop
        $startLength = $centerLength - 1;                   //set start length of outer loop
        $endHeight = $startHeight + self::SUB_BOARD_HEIGHT; //set finishing height of inner loop
        $endLength = $startLength + self::SUB_BOARD_LENGTH; //set finishing length of inner loop
        
        for ($h = $startHeight ; $h < $endHeight; $h++) {
            for ($l = $startLength ; $l < $endLength; $l++) {
                
                if ($h == $centerHeight && $l == $centerLength){ continue;}
                if ($h < 0 || $h >= $this->height){ continue;}
                if ($l < 0 || $l >= $this->length){ continue;} 
                
                $adjacentMines += $this->board[$h][$l];
            }
        }
        return $adjacentMines;
    }

    public function display()
    {
        $board = $this->getBoard();

        // Print column numbers
        echo "  ";
        for ($j = 0; $j < $this->length; $j++) {
            $this->formatCellOutput($j + 1);
        }
        echo PHP_EOL;
        echo "  ";
        for ($j = 0; $j < $this->length; $j++) {
            $this->formatCellOutput('_');
        }
        echo PHP_EOL;

        // Print board with row numbers and cells
        for ($i = 0; $i < $this->height; $i++) {
            // Print row number
            $this->formatCellOutput(output:$i + 1,  suffix:'|');

            // Print cells
            for ($j = 0; $j < $this->length; $j++) {
                //remove padding for the first column of cells
                $positionPadflag = $j!=0;
                if ($board[$i][$j]->getHasMine()){
                    $this->formatCellOutput(output:self::SHOW_MINE, pad:$positionPadflag);
                }else{
                    if ($board[$i][$j]->getValue()){
                        $this->formatCellOutput(output:$board[$i][$j]->getValue(), pad:$positionPadflag);
                    }else{
                        $this->formatCellOutput(output:' ', pad:$positionPadflag);
                    }
                }
                
            }
            echo '|' . PHP_EOL;
        }
    }


    public function formatCellOutput($output, $pad = true, $suffix = ' '): void
    {
        if ($pad) $output = str_pad($output, 2, ' ', STR_PAD_LEFT);
        echo $output . $suffix;
    }
}