<?php

class ResponseHandler {
    public static function sendSuccessResponse(array $data): void
    {
        self::sendResponse($data, 200);
    }

    public static function sendBadRequestResponse(string $message): void
    {
        self::sendResponse(["message"=>$message], 400);
    }

    public static function sendFailResponse(string $message): void
    {
        self::sendResponse(["message"=>$message], 500);
    }

    private static function sendResponse($responseContent, $statusCode): void
    {
        http_response_code($statusCode);
        echo json_encode($responseContent);
        exit;
    }
}

?>
