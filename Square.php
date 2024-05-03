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

    // Setters and Getters

    public function setValue(int $value): void {
        $this->value = $value;
    }

    public function getValue(): int {
        return $this->value;
    }

    public function setLongitude(int $longitude): void {
        $this->longitude = $longitude;
    }

    public function getLongitude(): int {
        return $this->longitude;
    }

    public function setLatitude(int $latitude): void {
        $this->latitude = $latitude;
    }

    public function getLatitude(): int {
        return $this->latitude;
    }

    public function setHasMine(bool $hasMine): void {
        $this->hasMine = $hasMine;
    }

    public function getHasMine(): bool {
        return $this->hasMine;
    }

    public function setIsFlagged(bool $isFlagged): void {
        $this->isFlagged = $isFlagged;
    }

    public function getIsFlagged(): bool {
        return $this->isFlagged;
    }

    public function setIsRevealed(bool $isRevealed): void {
        $this->isRevealed = $isRevealed;
    }

    public function getIsRevealed(): bool {
        return $this->isRevealed;
    }

    public function setIsEdgeSquare(bool $isEdgeSquare): void {
        $this->isEdgeSquare = $isEdgeSquare;
    }

    public function getIsEdgeSquare(): bool {
        return $this->isEdgeSquare;
    }

    public function setNorth(Square $north): void {
        $this->north = $north;
    }

    public function getNorth(): Square {
        return $this->north;
    }

    public function setNorthEast(Square $northEast): void {
        $this->northEast = $northEast;
    }

    public function getNorthEast(): Square {
        return $this->northEast;
    }

    public function setEast(Square $east): void {
        $this->east = $east;
    }

    public function getEast(): Square {
        return $this->east;
    }

    public function setSouthEast(Square $southEast): void {
        $this->southEast = $southEast;
    }

    public function getSouthEast(): Square {
        return $this->southEast;
    }

    public function setSouth(Square $south): void {
        $this->south = $south;
    }

    public function getSouth(): Square {
        return $this->south;
    }

    public function setSouthWest(Square $southWest): void {
        $this->southWest = $southWest;
    }

    public function getSouthWest(): Square {
        return $this->southWest;
    }

    public function setWest(Square $west): void {
        $this->west = $west;
    }

    public function getWest(): Square {
        return $this->west;
    }

    public function setNorthWest(Square $northWest): void {
        $this->northWest = $northWest;
    }

    public function getNorthWest(): Square {
        return $this->northWest;
    }

    public function setNeighbors(array $neighbors){
        $this->setNorth($neighbors[0]);
        $this->setNorthEast($neighbors[1]);
        $this->setEast($neighbors[2]);
        $this->setSouthEast($neighbors[3]);
        $this->setSouth($neighbors[4]);
        $this->setSouthWest($neighbors[5]);
        $this->setWest($neighbors[6]);
        $this->setNorthWest($neighbors[7]);
    }
}