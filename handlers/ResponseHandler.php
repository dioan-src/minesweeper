<?php

class ResponseHandler {
    public static function sendSuccessResponse($data) {
        self::sendResponse($data, 200);
    }

    public static function sendBadRequestResponse($message) {
        self::sendResponse(["message"=>$message], 400);
    }

    public static function sendFailResponse($message) {
        self::sendResponse(["message"=>$message], 500);
    }

    private static function sendResponse($responseContent, $statusCode) {
        http_response_code($statusCode);
        echo json_encode($responseContent);
        exit;
    }
}

?>
