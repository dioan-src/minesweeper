<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../models/Board.php';
require_once __DIR__ . '/../handlers/ResponseHandler.php';
require_once __DIR__ . '/../utils/RequestValidation.php';
require_once __DIR__ . '/../handlers/CustomSessionHandler.php';
require_once __DIR__ . '/../resources/BoardResource.php';
CustomSessionHandler::initSession();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try{
        //validate data
        if (RequestValidation::validateActionOnBoard($_GET) == false) ResponseHandler::sendBadRequestResponse('Missing Parameters');
        //assign vars
        $boardRows = $_GET['boardRows'];
        $boardColumns = $_GET['boardColumns'];
        //fetch board
        $board = CustomSessionHandler::fetchSessionBoard($boardRows, $boardColumns);
        if ($board) {
            ResponseHandler::sendSuccessResponse(BoardResource::toArray($board));
        }else{
            ResponseHandler::sendSuccessResponse(['message'=>'No Game Running']);
        }
    }catch(Exception $e){
        ResponseHandler::sendFailResponse($e->getMessage());    
    }
} else {
    ResponseHandler::sendBadRequestResponse("Bad Request");
}
