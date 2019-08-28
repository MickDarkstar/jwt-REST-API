<?php
class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function Login()
    {
        $data = json_decode(file_get_contents("php://input"));
        $service = new UserService();
        $emailExists = $service->emailExists($data->email);
        if ($emailExists) {
            $user = $service->getByEmail($data->email);
            MiddleWare::VerifyPassword($data, $user);
        } else {
            echo json_encode(
                array(
                    "message" => "Unsuccessful login: E-mail does not exist"
                )
            );
        }

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
        $service = new UserService();
        $result = $service->all();
        echo json_encode(
            array(
                "message" => "Saved user",
                "data" => null
            )
        );
    }
}
