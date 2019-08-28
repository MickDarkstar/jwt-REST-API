<?php
include_once 'MiddleWare.settings.php';

use \Firebase\JWT\JWT;

/**
 * MiddleWare for authorization with jwt-token
 *
 * @version 1.0
 * @author Micke@tempory.org
 */
class MiddleWare
{
    public function __construct()
    { }

    public static function Authorize()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization']) == false) {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied. Auth-header missing"));
            exit;
        }
        $TOKEN = $headers['Authorization'];
        if ($TOKEN) {
            try {
                return JWT::decode($TOKEN, SECRET_KEY, array('HS256'));
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(array(
                    "message" => "Access denied. Invalid access-token",
                    "error" => $e->getMessage()
                ));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied."));
            exit;
        }
        return true;
    }

    public static function ValidateToken()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization']) == false) {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied. Auth-header missing"));
            exit;
        }
        $TOKEN = $headers['Authorization'];
        if ($TOKEN) {
            try {
                $decoded = JWT::decode($TOKEN, SECRET_KEY, array('HS256'));
                http_response_code(200);
                echo json_encode(array(
                    "message" => "Access granted.",
                    "data" => $decoded->data
                ));
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(array(
                    "message" => "Access denied. Invalid access-token",
                    "error" => $e->getMessage()
                ));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied."));
            exit;
        }
        return true;
    }
}
