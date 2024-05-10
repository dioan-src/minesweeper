<?php
class SquareParameters
{
    const HAS_MINE = true;
    const NOT_HAS_MINE = false;
    const REVEALED = true;
    const NOT_REVEALED = false;
    const FLAGGED = true;
    const NOT_FLAGGED = false;
    
    const NEIGHBORS = [
        'North' => [
            'heightDiffFromCenter' => -1,
            'lengthDiffFromCenter' => 0,
        ],
        'NorthEast' => [
            'heightDiffFromCenter' => -1,
            'lengthDiffFromCenter' => +1,
        ],
        'East' => [
            'heightDiffFromCenter' => 0,
            'lengthDiffFromCenter' => +1,
        ],
        'SouthEast' => [
            'heightDiffFromCenter' => +1,
            'lengthDiffFromCenter' => +1,
        ],
        'South' => [
            'heightDiffFromCenter' => +1,
            'lengthDiffFromCenter' => 0,
        ],
        'SouthWest' => [
            'heightDiffFromCenter' => +1,
            'lengthDiffFromCenter' => -1,
        ],
        'West' => [
            'heightDiffFromCenter' => 0,
            'lengthDiffFromCenter' => -1,
        ],
        'NorthWest' => [
            'heightDiffFromCenter' => -1,
            'lengthDiffFromCenter' => -1,
        ],
    ];
}