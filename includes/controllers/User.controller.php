<?php
class UserController extends BaseController
{
    function __construct()
    {
        parent::__construct();
    }

    public static function AllUsers()
    {
        $service = new UserService();
        $result = $service->all();
        echo json_encode(
            array(
                "message" => "All users",
                "data" => $result
            )
        );
    }

    public static function SaveUser()
    {
        echo json_encode(
            array(
                "message" => "Saved user",
                "data" => null
            )
        );
    }
}
