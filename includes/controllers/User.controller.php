<?php
class UserController extends BaseController
{
    public static function AllUsers()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $service = new UserService();
            $result = $service->all();
            echo $result;
            http_response_code(200);
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo 'POST';
        } else {
            http_response_code(405);
        }
    }
}
