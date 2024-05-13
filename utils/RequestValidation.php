<?php
class RequestValidation
{
    public static function validateActionOnSquare(array $post): bool
    {
        if( !isset($post['row']) || !is_numeric($post['row']) ) return false;
        if( !isset($post['column']) || !is_numeric($post['column']) ) return false;
        if ( !self::validateActionOnBoard($post)) return false;
        return true;
    }

    public static function validateActionOnBoard(array $post): bool
    {
        if( !isset($post['boardRows']) || !is_numeric($post['boardRows']) ) return false;
        if( !isset($post['boardColumns']) || !is_numeric($post['boardColumns']) ) return false;
        return true;
    }
}