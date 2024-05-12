<?php
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
        if (RequestValidation::validateOpenSquare($postedData) == false) ResponseHandler::sendBadRequestResponse('Missing Parameters');
        //assign vars
        $row = $postedData['row'];
        $column = $postedData['column'];
        $boardRows = $postedData['boardRows'];
        $boardColumns = $postedData['boardColumns'];
        
        //fetch board
        $board = CustomSessionHandler::fetchSessionBoard($boardRows, $boardColumns);
        // flag only if game has been already set
        if ($board) {
            $board->flagSquare( $board->getSquareAt($row, $column) );
            //save board
            CustomSessionHandler::storeBoard($boardRows, $boardColumns, $board);
        }
        
        ResponseHandler::sendSuccessResponse(BoardResource::toArray($board));
    }catch(Exception $e){
        ResponseHandler::sendFailResponse($e->getMessage());    
    }
} else {
    ResponseHandler::sendBadRequestResponse("Bad Request");
}
