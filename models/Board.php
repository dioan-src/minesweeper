<?php
require_once __DIR__ . '/Square.php';
require_once __DIR__ . '/../utils/SquareParameters.php';
require_once __DIR__ . '/../utils/BoardParameters.php';

// class for  the minsweeper board
class Board
{
    private int $length;
    private int $height;
    private int $minesNum;    
    private array $grid;
    private int $revealedCounter;
    private bool $isGameOver;

    public function __construct(int $length = 0, int $height = 0, array $presetGrid = null, int $seedHeight = 0, int $seedLength = 0)
    {
        $this->setRevealedCounter(0);
        $this->setIsGameOver(false);
        if($presetGrid) {
            $this->height = count($presetGrid);
            $this->length = count(array_map(null, ...$presetGrid));
            $this->minesNum = $numberOfOnes = array_sum(array_map('array_sum', $presetGrid));
            $finalGrid = $presetGrid;
        }else if($length && $height){
            $this->length = $length;
            $this->height = $height;
            $this->minesNum = (int)$length*$height*BoardParameters::MINES_PERCENTAGE;
            $zeros = $this->create2dArrayOfZeros();
            $finalGrid = $this->generateMines($zeros, $seedHeight, $seedLength);
        }
        $this->setGridMadeOfSquares($finalGrid);
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

    // Setter for $revealedCounter
    public function setRevealedCounter(int $updatedCounter): void {
        $this->revealedCounter = $updatedCounter;
    }

    // Getter for $revealedCounter
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

    // Setter for $isGameOver
    public function setIsGameOver(bool $isGameOver): void {
        $this->isGameOver = $isGameOver;
    }

    // Getter for $isGameOver
    public function getIsGameOver(): bool {
        return $this->isGameOver;
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
    public function generateMines(array $grid,int $seedHeight, int $seedLength): array
    {
        $minesGenerated = 0;
        $totalCells = $this->height * $this->length;

        while ($minesGenerated < $this->minesNum) {
            //get random position in 2d array
            $random = random_int(0, $totalCells-1);
            $randomHeight = intdiv($random, $this->length);
            $randomLength = $random % $this->length;
            if ($this->checkMineIsTooCloseToSeed($randomHeight, $randomLength, $seedHeight, $seedLength)) continue;
            //set only if position doesnt already contain a mine
            if ($grid[$randomHeight][$randomLength] == 0) {
                $grid[$randomHeight][$randomLength] = 1;
                $minesGenerated++;
            }
        }
        return $grid;
    }

    public function checkMineIsTooCloseToSeed(int $randomHeight, int $randomLength, int $seedHeight, int $seedLength): bool
    {
        return abs($randomHeight - $seedHeight) <= 1 && abs($randomLength - $seedLength) <= 1;
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
    * Checks if game is not over
    */
    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }

    /**
    * what happens when player touches an already revealed Square
    */
    public function touchSquare(?Square $square): void
    {
        if (!$square) return;
        if ($square->getIsRevealed() == false) return;
        if ($square->isCorrectlyFlagged()) $this->revealAllNeighbors($square);
    }

    /**
     * Toggles the flag status of a closed Square on the game board.
     */
    public function flagSquare(?Square $square): void
    {
        if (!$square) return;
        if ($square->getIsRevealed()) return;
        //flag/unflag square
        $square->setIsFlagged( !$square->getIsFlagged() );
    }

    /**
     * Reveals a closed Square on the game board. 
     * If the Square contains a mine, the game is set as over.
     * Otherwise, if the Square has no neighboring mines, all neighboring squares are revealed.
     */
    public function revealSquare(?Square $square): void
    {
        if (!$square) return;
        if ($square->getIsRevealed()) return;
        $square->setIsRevealed(true);
        $this->setRevealedCounter( $this->getRevealedCounter()+1);
        //if square has mine, set game as over and get out if func
        if ($square->getHasMine()){$this->setIsGameOver(true); return;}
        // //if square has no neighboring mines, reveale all neighboring squares
        if ($square->getNeighboringMines() == 0) $this->revealAllNeighbors($square);
        //set game is over if all non-mined cells have been revealed
        if ($this->areAllNonMinedCellsRevealed()) $this->setIsGameOver(true);
    }

    /**
     * Reveals all neighboring squares of a given Square that haven't been revealed yet.
     * Iterates through all neighboring squares of the provided Square.
     * If the neighboring square has no neighboring mines, all of its neighboring 
     * squares are also revealed.
     */
    public function revealAllNeighbors(?Square $square): void
    {
        foreach (array_keys(SquareParameters::NEIGHBORS) as $neighborName) {
            $neighbor = $square->getNeighborWithName($neighborName);
            if (!$neighbor) continue;
            if ($neighbor->getIsRevealed()) continue;
            //dont open if square has mine (used by touch func)
            if ($neighbor->getHasMine()) continue;
            $neighbor?->setIsRevealed(true);
            $this->setRevealedCounter( $this->getRevealedCounter()+1);
            //if square has no neighboring mines, reveal all neighboring squares
            if ($neighbor->getNeighboringMines() == 0) $this->revealAllNeighbors($neighbor);
        }
        //set game is over if all non-mined cells have been revealed
        if ($this->areAllNonMinedCellsRevealed()) $this->setIsGameOver(true);
    }
}