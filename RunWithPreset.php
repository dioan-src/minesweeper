<?php
require_once __DIR__ . '/handlers/TerminalGameHandler.php';

//fetch preset board for testing
$presetBoard = include 'data/preset8x8.php';
// $presetBoard = include 'data/preset16x16.php';
// $presetBoard = include 'data/preset30x16.php';

TerminalGameHandler::playWithPresetBoard($presetBoard);