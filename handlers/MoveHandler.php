<?php
require_once __DIR__ . '/../utils/GameParameters.php';

/**
 * Handles the processing of user moves in the Minesweeper game.
 */
class MoveHandler
{

    /**
     * Prompts the user to enter a move and returns the input string
     */
    public static function getMove(): string
    {
        // echo 'Available Moves: o | f | t'. PHP_EOL;
        // echo 'o -> open Square | f -> flag/unflag square | t -> touch square'. PHP_EOL;
        // echo 'Format of move MoveName-column-row e.g., t-2-3'. PHP_EOL;
        return readline("Enter your move: ");
    }

    /**
     * Validates and executes the provided move on the given board
     */
    public static function handleMove(string $moveInput, Board $board): void
    {
        $moveParts = explode('-', $moveInput);
        if ( !self::validateMove($moveParts) ) {echo 'Wrong Move. You can follow instructions right? '. PHP_EOL; return;}
        list($move, $row, $column) = self::extractMoveDetails($moveParts);
        if ( !method_exists($board, $move) ) return;
        $board->$move( $board->getSquareAt($row, $column) );
    }

    /**
     * Validates the format of the move input
     */
    public static function validateMove(array $moveParts): bool
    {
        if (count($moveParts) != 3) return false;
        if ( in_array($moveParts[0],[GameParameters::MOVE_FLAG, GameParameters::MOVE_OPEN, GameParameters::MOVE_TOUCH]) == false) return false;
        if ( is_numeric($moveParts[1]) == false) return false;
        if ( is_numeric($moveParts[2]) == false) return false;
        return true;
    }

    /**
     * Extracts the move details from the move parts
     */
    public static function extractMoveDetails(array $moveParts): array
    {
        switch ($moveParts[0]) {
            case GameParameters::MOVE_FLAG:
                $move = 'flagSquare';
                break;
            case GameParameters::MOVE_TOUCH:
                $move = 'touchSquare';
                break;
            case GameParameters::MOVE_OPEN:
                $move = 'revealSquare';
                break;
        }
        $row = (int)$moveParts[1] - 1;
        $column = (int)$moveParts[2] - 1;
        return [$move, $row, $column];
    }

    //functions for starting move
    public static function getStart(): array|bool
    {
        $startFlag=true;
        while($startFlag){
            $seed = readline("Enter your starting move eg 3-5: ");
            
            $parts = explode('-', $seed);
            if (count($parts) != 2) continue;
            if (is_numeric($parts[0]) == false) continue;
            if (is_numeric($parts[1]) == false) continue;
            $startFlag = false;
        }
        $row = (int)$parts[0];
        $column = (int)$parts[1];
        return [$row, $column];
    }
}