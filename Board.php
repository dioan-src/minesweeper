<?php
require_once __DIR__ . '/Square.php';
require_once __DIR__ . '/SquareParameters.php';

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
        $this->setNeighborsOfSquares();
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
    public function setGrid(array $grid): void {
        $this->grid = $grid;
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
                $gridOfSquares[$i][$j] = new Square( $this->getAdjacentMines($i, $j),(bool)$this->getGrid()[$i][$j], SquareParameters::NOT_FLAGGED, SquareParameters::NOT_REVEALED);
            }
            
        }
        $this->setGrid($gridOfSquares);
    }

    /**
    * Calculates the number of adjacent mines surrounding a given square on the minesweeper board.
    */
    public function getAdjacentMines(int $centerHeight, int $centerLength): int
    {
        $adjacentMines = 0;
        // Calculate the starting and ending positions of the subgrid
        $startHeight = max(($centerHeight - 1), 0);                   //set start height of outer loop
        $startLength = max(($centerLength - 1), 0);                   //set start length of outer loop
        $endHeight = min(($centerHeight + self::SUB_GRID_DISTANCE_FROM_CENTER_HEIGHT), ($this->height - 1)); //set finishing height of inner loop
        $endLength = min(($centerLength + self::SUB_GRID_DISTANCE_FROM_CENTER_LENGTH), ($this->length - 1)); //set finishing length of inner loop
        // Iterate over each square in the subgrid
        for ($h = $startHeight ; $h <= $endHeight; $h++) {
            for ($l = $startLength ; $l <= $endLength; $l++) {
                // Exclude center element
                if ($h == $centerHeight && $l == $centerLength){ continue;}
                // Increment the count of adjacent mines if the square contains a mine
                $adjacentMines += $this->getGrid()[$h][$l];
            }
        }
        return $adjacentMines;
    }

    public function setNeighborsOfSquares()
    {
        $grid = $this->getGrid();

        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->length; $j++) {

                foreach (SquareParameters::NEIGHBORS as $neighborName => $direction) {
                    //set direction of neighbor and setter function based on neighbor name
                    $neighborHeight = $i + $direction['heightDiffFromCenter'];
                    $neighborLength = $j + $direction['lengthDiffFromCenter'];
                    $neighborFunc = 'set' . $neighborName ;
                    
                    //set Square as neighbor if exists or null
                    $grid[$i][$j]->$neighborFunc( $this->getSquareAt($neighborHeight, $neighborLength) );
                }
            }
        }
    }

    public function getSquareAt(int $height, int $length): ?Square
    {
        return $this->validateGridBoundaries($height, $length) ? $this->grid[$height][$length] : null;
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
                if ($this->getSquareAt($i, $j)->getHasMine()){
                    $this->formatCellOutput(output:self::SHOW_MINE, pad:$positionPadflag);
                }else{
                    if ($this->getSquareAt($i, $j)->getNeighboringMines()){
                        $this->formatCellOutput(output:$this->getSquareAt($i, $j)->getNeighboringMines(), pad:$positionPadflag);
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

    public function validateGridBoundaries(int $height, int $length)
    {
        return ($height >= 0 && $height < $this->height) &&
            ($length >= 0 && $length < $this->length);
    }
}