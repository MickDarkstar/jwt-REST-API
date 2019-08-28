<?php
//** Mostly for syntax sugar and less code */
class Response
{
    public static function OK($data, $message = null)
    {
        self::Status(200);
        self::Return($message, $data);
    }

    public static function AccessDenied($message = null, $data)
    {
        if(isset($message) === false) {
            $message = "Access denied.";
        }
        self::Status(401);
        self::Return($message);
    }

    public static function MethodNotAllowed($message = null)
    {
        self::Status(405);
        self::Return($message);
    }

    public static function InternalServerError($message = null)
    {
        self::Status(500);
        self::Return($message);
    }

    private static function Status($code)
    {
        http_response_code($code);
    }

    private static function Return($message, $data = null)
    {
        $array = array(
            "message" => $message,
            "data" => $data
        );

        echo json_encode(
            $array
        );
    }
}
