<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../models/Board.php';
require_once __DIR__ . '/../handlers/ResponseHandler.php';
require_once __DIR__ . '/../utils/RequestValidation.php';
require_once __DIR__ . '/../handlers/CustomSessionHandler.php';
require_once __DIR__ . '/../resources/BoardResource.php';
CustomSessionHandler::initSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        //read data
        $rawData = file_get_contents('php://input');
        $postedData = json_decode($rawData, true);
        //validate data
        if (RequestValidation::validateActionOnSquare($postedData) == false) ResponseHandler::sendBadRequestResponse('Missing Parameters');
        //assign vars
        $row = $postedData['row'];
        $column = $postedData['column'];
        $boardRows = $postedData['boardRows'];
        $boardColumns = $postedData['boardColumns'];
        
        //fetch board
        $board = CustomSessionHandler::fetchSessionBoard($boardRows, $boardColumns);
        // check if Board exists, if not, initialize it
        if (!$board) {
            $board = new Board(length:$boardRows, height:$boardColumns, seedHeight:$row, seedLength:$column);
        }
        $board->revealSquare( $board->getSquareAt($row, $column) );

        //check if game is still going and either save it or delete it
        if($board->isGameOver()){
            CustomSessionHandler::deleteBoard($boardRows, $boardColumns);
        }else{
            CustomSessionHandler::storeBoard($boardRows, $boardColumns, $board);
        }
        
        ResponseHandler::sendSuccessResponse(BoardResource::toArray($board));
    }catch(Exception $e){
        ResponseHandler::sendFailResponse($e->getMessage());    
    }
} else {
    ResponseHandler::sendBadRequestResponse("Bad Request");
}
