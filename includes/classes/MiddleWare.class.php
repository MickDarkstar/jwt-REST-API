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
            // tell the user login failed
            Response::AccessDenied("Login failed. Wrong password");
        }
    }

    /**
     * If user is not authorized then a response is sent to client and code execution stops 
     */
    public static function Authorize()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization']) == false) {
            Response::AccessDenied("Login failed. Header not set");
            exit;
        }
        $TOKEN = $headers['Authorization'];
        if ($TOKEN) {
            try {
                return JWT::decode($TOKEN, SECRET_KEY, array('HS256'));
            } catch (Exception $e) {
                Response::AccessDenied("Access denied. Invalid token", $e->getMessage());
            }
        } else {
            Response::AccessDenied("Login failed. Wrong password");
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
        Response::OK($decoded->data, "Access granted.");
    }
}
