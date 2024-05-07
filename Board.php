<?php
require_once __DIR__ . '/Square.php';
require_once __DIR__ . '/SquareParameters.php';
require_once __DIR__ . '/BoardParameters.php';

// class for  the minsweeper board
class Board
{
    private int $length;
    private int $height;
    private int $minesNum;    
    private array $grid;
    private int $revealedCounter;

    public function __construct(int $length = 0, int $height = 0, array $presetGrid = null)
    {
        $this->revealedCounter = 0;
        if($presetGrid) {
            //count of array -> height
            $this->height = count($presetGrid);
            //count of transposed array -> height
            $this->length = count(array_map(null, ...$presetGrid));
            //count of summed 1s
            $this->minesNum = $numberOfOnes = array_sum(array_map('array_sum', $presetGrid));
            $finalGrid = $presetGrid;
        }else if($length && $height){
            $this->length = $length;
            $this->height = $height;
            $this->minesNum = (int)$length*$height*BoardParameters::MINES_PERCENTAGE;
            $zeros = $this->create2dArrayOfZeros();
            $finalGrid = $this->generateMines($zeros);
        }
        $this->setGridMadeOfSquares($finalGrid);
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
    public function setRevealedCounter(int $updatedCounter): void {
        $this->revealedCounter = $updatedCounter;
    }

    // Getter for $minesNum
    public function getRevealedCounter(): int {
        return $this->revealedCounter;
    }

    // Setter for $minesNum
    public function setGrid(array $grid): void {
        $this->grid = $grid;
    }

    // Getter for $minesNum
    public function getGrid(): array {
        return $this->grid;
    }

    /**
    * Creates a 2d array containing only 0s, based on the dimenstions set in the constructor
    */
    public function create2dArrayOfZeros(): array
    {
        $row = array_fill(0, $this->length, 0);
        return array_fill(0, $this->height, $row);
    }

    /**
    * Randomly generates mines in the grid, based on the minesNum set in the constructor
    */
    public function generateMines(array $grid): array
    {
        $minesGenerated = 0;
        $totalCells = $this->height * $this->length;

        while ($minesGenerated < $this->minesNum) {
            //get random position in 2d array
            $random = random_int(0, $totalCells-1);
            $randomHeight = intdiv($random, $this->length);
            $randomLength = $random % $this->length;
            //set only if position doesnt already contain a mine
            if ($grid[$randomHeight][$randomLength] == 0) {
                $grid[$randomHeight][$randomLength] = 1;
                $minesGenerated++;
            }
        }
        return $grid;
    }

    /**
    * Creates a grid out of Squares based on the generated grid
    * of 1s(cells containing a mine) and 0s (cells not containing a mine)
    */
    public function setGridMadeOfSquares(array $grid): void
    {
        $gridOfSquares = [];
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->length; $j++) {
                $gridOfSquares[$i][$j] = new Square(
                    $this->getAdjacentMines($i, $j, $grid),
                    (bool)$grid[$i][$j], 
                    SquareParameters::NOT_FLAGGED, 
                    SquareParameters::NOT_REVEALED
                );
            }
        }
        $this->setGrid($gridOfSquares);
    }

    /**
    * Calculates the number of adjacent mines surrounding a given(center) square on the minesweeper board.
    */
    public function getAdjacentMines(int $centerHeight, int $centerLength, array $grid): int
    {
        $adjacentMines = 0;
        // Calculate the starting and ending positions of the subgrid
        $startHeight = max(($centerHeight - 1), 0);                   //set start height of outer loop
        $startLength = max(($centerLength - 1), 0);                   //set start length of outer loop
        $endHeight = min(($centerHeight + BoardParameters::SUB_GRID_DISTANCE_FROM_CENTER_HEIGHT), ($this->height - 1)); //set finishing height of inner loop
        $endLength = min(($centerLength + BoardParameters::SUB_GRID_DISTANCE_FROM_CENTER_LENGTH), ($this->length - 1)); //set finishing length of inner loop
        // Iterate over each square in the subgrid
        for ($h = $startHeight ; $h <= $endHeight; $h++) {
            for ($l = $startLength ; $l <= $endLength; $l++) {
                // Exclude center element
                if ($h == $centerHeight && $l == $centerLength){ continue;}
                // Increment the count of adjacent mines if the square contains a mine
                $adjacentMines += $grid[$h][$l];
            }
        }
        return $adjacentMines;
    }

    /**
    * Set the 8 neighboring Squares of every Square in the grid
    */
    public function setNeighborsOfSquares()
    {
        $grid = $this->getGrid();

        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->length; $j++) {

                foreach (SquareParameters::NEIGHBORS as $neighborName => $direction) {
                    //set direction of neighbor and setter function based on neighbor name
                    $neighborHeight = $i + $direction['heightDiffFromCenter'];
                    $neighborLength = $j + $direction['lengthDiffFromCenter'];
                    
                    //set Square as neighbor if exists or null
                    $this->getSquareAt($i, $j)->setNeighborWithName($neighborName, $this->getSquareAt($neighborHeight, $neighborLength));
                }
            }
        }
    }

    /**
    * Fetches the Square in the coordinates given if it exists, or return null 
    */
    public function getSquareAt(int $height, int $length): ?Square
    {
        return $this->validateGridBoundaries($height, $length) ? $this->grid[$height][$length] : null;
    }

    /**
    * Display the current position of the grid of Squares
    */
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
                    $this->formatCellOutput(output:BoardParameters::MINE_DISPLAY_SYMBOL, pad:$positionPadflag);
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

    /**
    * Format the output, used by the display function
    */
    public function formatCellOutput($output, $pad = true, $suffix = ' '): void
    {
        if ($pad) $output = str_pad($output, 2, ' ', STR_PAD_LEFT);
        echo $output . $suffix;
    }

    /**
    * Checks if coordinates are within the bounds of the grid
    */
    public function validateGridBoundaries(int $height, int $length)
    {
        return ($height >= 0 && $height < $this->height) &&
            ($length >= 0 && $length < $this->length);
    }

    /**
    * Checks if all non-mined squares on the grid have been revealed
    */
    public function areAllNonMinedCellsRevealed(): bool
    {
        return $this->revealedCounter == $this->length*$this->height-$this->minesNum;
    }

    /**
    * what happens when player touches an already revealed Square
    */
    public function touchSquare(int $height, int $length): void
    {
        //TODO fetch square
        //TODO check square is revealed
        //TODO check neighboring squares of center have been correctly flagged
        //TODO use revealAllNeighbors
    }

    /**
    * what happens when a player flags/unflags an closed Square
    */
    public function flagSquare(int $height, int $length): void
    {
        //TODO fetch square
        //TODO check square is hidden
        //TODO flag/unflag square
    }

    /**
    * what happens when a player reveals a closed Square
    */
    public function revealSquare(int $height, int $length): void
    {
        //TODO fetch square
        //TODO check square is hidden
        //TODO reveal square
        //TODO use revealAllNeighbors if revealed square has 0 neighboringMines
    }

    /**
    * reveal all neighbors of a square
    */
    public function revealAllNeighbors(int $height, int $length): void
    {
        //TODO reveal all neighboring squares
        //TODO if any square has 0 neighboringMines, call this function again
    }
}