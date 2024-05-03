<?php 

// class for every Square on the minsweeper board
class Square{
    
    private int $value; 
    private int $longitude; // east west -> x
    private int $latitude;  // north south -> y
    private bool $hasMine;
    private bool $isFlagged;
    private bool $isRevealed;
    private bool $isEdgeSquare;

    private Square $north;
    private Square $northEast;
    private Square $east;
    private Square $southEast;
    private Square $south;
    private Square $southWest;
    private Square $west;
    private Square $northWest;

    public function __construct(
        int $value,
        int $latitude,
        int $longitude,
        bool $hasMine,
        bool $isFlagged,
        bool $isRevealed)
    {
        $this->value = $value;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->hasMine = $hasMine;
        $this->isFlagged = $isFlagged;
        $this->isRevealed = $isRevealed;
    }

    public function setNeighbors(array $neighbors){
        $this->north = $neighbors[0];
        $this->northEast = $neighbors[1];
        $this->east = $neighbors[2];
        $this->southEast = $neighbors[3];
        $this->south = $neighbors[4];
        $this->southWest = $neighbors[5];
        $this->west = $neighbors[6];
        $this->northWest = $neighbors[7];
    }
}