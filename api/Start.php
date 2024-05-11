<?php
require_once __DIR__ . '/../handlers/ResponseHandler.php';
require_once __DIR__ . '/../utils/RequestValidation.php';

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
        
        //handleMove
        
        ResponseHandler::sendSuccessResponse($responseData);
    }catch(Exception $e){
        ResponseHandler::sendFailResponse($e->getMessage());    
    }
} else {
    ResponseHandler::sendBadRequestResponse("Bad Request");
}
