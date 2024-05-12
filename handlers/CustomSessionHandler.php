<?php
class CustomSessionHandler
{
    public static function initSession() : void
    {
        session_start();
    }

    public static function fetchSessionBoard(int $row, int $column): ?Board
    {
        return $_SESSION[self::buildSessionBoardName($row, $column)] ?? null;
    }

    public static function storeBoard(int $row, int $column, Board $board): void
    {
        $_SESSION[self::buildSessionBoardName($row, $column)] = $board; 
    }

    public static function buildSessionBoardName(int $row, int $column): string
    {
        return $row . 'x' . $column;
    }

    public static function deleteBoard(int $row, int $column): void
    {
        unset($_SESSION[self::buildSessionBoardName($row, $column)]);
    }
}