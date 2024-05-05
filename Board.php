<?php
require_once __DIR__ . '/Square.php';

// class for  the minsweeper board
class Board
{
    private int $length;
    private int $height;
    private int $minesNum;    
    private array $board;
    const SUB_BOARD_HEIGHT = 3;
    const SUB_BOARD_LENGTH = 3;
    const IS_HIDDEN = 0;
    const SHOW_MINE = 'X ';
    const SHOW_HIDDEN_SQUARE = '\u{25A0} ';

    public function __construct(int $length, int $height, int $minesNum)
    {
        $this->length = $length;
        $this->height = $height;
        $this->minesNum = $minesNum;
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

        //TODO CHANGE THIS - I DONT WANT TO SET A BOARD AND THEN RESET IT.
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
        return array_chunk($finalArray, $this->height);;
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
        // echo 'center is ' . $centerHeight . ' ' . $centerLength . ' ';echo PHP_EOL;
        // echo 'limits are ' . $this->height . ' ' . $this->length . ' ';echo PHP_EOL;

        $startHeight = $centerHeight - 1;                   //set start height of outer loop
        $startLength = $centerLength - 1;                   //set start length of outer loop
        $endHeight = $startHeight + self::SUB_BOARD_HEIGHT; //set finishing height of inner loop
        $endLength = $startLength + self::SUB_BOARD_LENGTH; //set finishing length of inner loop
        
        for ($h = $startHeight ; $h < $endHeight; $h++) {
            for ($l = $startLength ; $l < $endLength; $l++) {
                // echo $h . ' ' . $l . ' ';
                //exclude center cell
                if ($h == $centerHeight && $l == $centerLength){ continue;}
                // echo ' after 1';
                //exclude cell out of array
                if ($h < 0 || $h >= $this->height){ continue;}
                // echo ' after 2';
                if ($l < 0 || $l >= $this->length){ continue;} 
                // echo ' after 3';    
                // echo ' added ' . $this->board[$h][$l] . ' ';
                $adjacentMines += $this->board[$h][$l];
                // echo PHP_EOL;
            }
        }
        // echo ' got ' . $adjacentMines;
        // die();
        return $adjacentMines;
    }

    public function display()
    {
        $board = $this->getBoard();

        // Print column numbers
        echo "   ";
        for ($j = 0; $j < $this->length; $j++) {
            echo $j + 1 . " ";
        }
        echo PHP_EOL;
        echo '   _ _ _ _ _ _ _ _' . PHP_EOL;

        // Print board with row numbers and cells
        for ($i = 0; $i < $this->height; $i++) {
            // Print row number
            echo $i + 1 . "| ";

            // Print cells
            for ($j = 0; $j < $this->length; $j++) {
                if ($board[$i][$j]->getHasMine()){
                    echo self::SHOW_MINE;
                }else{
                    if ($board[$i][$j]->getValue()){
                        echo $board[$i][$j]->getValue() . ' ';
                    }else{
                        echo '  ';
                    }
                }
                // echo ($board[$i][$j]->getHasMine()) ? 'X ' : $board[$i][$j]->getValue() . ' ' ;
            }
            echo PHP_EOL;
        }
    }
}