<?php

// class for  the minsweeper board
class Board
{
    private int $length;
    private int $height;
    private int $minesNum;    
    private array $board;

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
        $minesArray = array_fill(0, ($this->minesNum), 1);
        $nonMinesArray = array_fill(0, ($this->height*$this->length-$this->minesNum), 0);
        $finalArray = array_merge($minesArray, $nonMinesArray);
        shuffle($finalArray);
        $boardValues = array_chunk($finalArray, $this->height);
        $this->setBoard($boardValues);
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

        // Print board with row numbers and cells
        for ($i = 0; $i < $this->height; $i++) {
            // Print row number
            echo $i + 1 . "| ";

            // Print cells
            for ($j = 0; $j < $this->length; $j++) {
                echo ($board[$i][$j] == 1) ? 'X ' : '0 ' ;
                // echo "\u{25A0} ";
                //TODO Use square for hidden values, X for mine and value or ' ' for the rest
            }
            echo PHP_EOL;
        }
    }

}