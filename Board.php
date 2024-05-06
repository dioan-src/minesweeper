<?php
require_once __DIR__ . '/Square.php';

// class for  the minsweeper board
class Board
{
    private int $length;
    private int $height;
    private int $minesNum;    
    private array $grid;
    const MINES_PERCENTAGE = 0.18;
    const SUB_GRID_DISTANCE_FROM_CENTER_HEIGHT = 1;
    const SUB_GRID_DISTANCE_FROM_CENTER_LENGTH = 1;
    const IS_HIDDEN = 0;
    const SHOW_MINE = '*';
    const SHOW_HIDDEN_SQUARE = '\u{25A0}';

    public function __construct(int $length = 0, int $height = 0, array $presetGrid = null)
    {
        if($presetGrid) {
            $this->setGrid($presetGrid);
            //count of array -> height
            $this->height = count($presetGrid);
            //count of transposed array -> height
            $this->length = count(array_map(null, ...$presetGrid));
            //count of summed 1s
            $this->minesNum = $numberOfOnes = array_sum(array_map('array_sum', $presetGrid));
        }else if($length && $height){
            $this->length = $length;
            $this->height = $height;
            $this->minesNum = (int)$length*$height*self::MINES_PERCENTAGE;
            $this->setGrid($this->createGridWithRandomizedMines());
        }
        $this->initializeSquaresOfGrid();
        //TODO set neighbors of squares
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
    public function setGrid(array $board): void {
        $this->grid = $board;
    }

    // Getter for $minesNum
    public function getGrid(): array {
        return $this->grid;
    }

    public function createGridWithRandomizedMines(): array
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

    public function initializeSquaresOfGrid(): void
    {

        $gridOfSquares = [];
        for ($i = 0; $i < $this->height; $i++) {

            for ($j = 0; $j < $this->length; $j++) {
                $gridOfSquares[$i][$j] = new Square( $this->getAdjacentMines($i, $j), $j, $i, (bool)$this->getGrid()[$i][$j], false, false);
            }
            
        }
        
        $this->setGrid($gridOfSquares);
    }

    public function getAdjacentMines(int $centerHeight, int $centerLength): int
    {
        $adjacentMines = 0;
        
        $startHeight = max(($centerHeight - 1), 0);                   //set start height of outer loop
        $startLength = max(($centerLength - 1), 0);                   //set start length of outer loop
        $endHeight = min(($centerHeight + self::SUB_GRID_DISTANCE_FROM_CENTER_HEIGHT), ($this->height - 1)); //set finishing height of inner loop
        $endLength = min(($centerLength + self::SUB_GRID_DISTANCE_FROM_CENTER_LENGTH), ($this->length - 1)); //set finishing length of inner loop
        
        for ($h = $startHeight ; $h <= $endHeight; $h++) {
            for ($l = $startLength ; $l <= $endLength; $l++) {
                //exclude center element
                if ($h == $centerHeight && $l == $centerLength){ continue;}
                
                $adjacentMines += $this->getGrid()[$h][$l];
            }
        }
        return $adjacentMines;
    }

    public function display()
    {
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
                if ($this->getGrid()[$i][$j]->getHasMine()){
                    $this->formatCellOutput(output:self::SHOW_MINE, pad:$positionPadflag);
                }else{
                    if ($this->getGrid()[$i][$j]->getValue()){
                        $this->formatCellOutput(output:$this->getGrid()[$i][$j]->getValue(), pad:$positionPadflag);
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