<?php
include_once 'MiddleWare.settings.php';

use \Firebase\JWT\JWT;

/**
 * MiddleWare for authorization with jwt-token
 * @version 1.0
 * @author Micke@tempory.org
 */
class MiddleWare
{
    public static function VerifyPassword($data, AppUser $user)
    {
        if (password_verify($data->password, $user->password)) {
            $token = array(
                "iss" => iss,
                "aud" => aud,
                "iat" => iat,
                "nbf" => nbf,
                "data" => array(
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email
                )
            );

            // set response code
            http_response_code(200);

            // generate jwt
            $jwt = JWT::encode($token, SECRET_KEY);
            echo json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "expiresIn" => nbf
                )
            );
            // login failed
        } else {

            // set response code
            http_response_code(401);

            // tell the user login failed
            echo json_encode(array("message" => "Login failed. Wrong password"));
        }
    }

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
    }
    /**
     * Validates and decodes jwt-token
     * response is array containing current user
     */
    public static function DecodeToken()
    {
        $decoded = self::Authorize();
        http_response_code(200);
        echo json_encode(array(
            "message" => "Access granted.",
            "data" => $decoded->data
        ));
    }
}
