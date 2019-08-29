<?php
final class UserController extends BaseController
{
    private static $init = false;
    private static $service;

    public function __construct()
    {
        parent::__construct();
    }

    public static function init() 
	{
		if (!self::$init) {
            self::$service = new UserService();
			self::$init = true; 
		}
    }
    
    public static function Login()
    {
        $data = json_decode(file_get_contents("php://input"));
        $emailExists = self::$service->emailExists($data->email);
        if ($emailExists) {
            $user = self::$service->getByEmail($data->email);
            MiddleWare::VerifyPassword($data, $user);
        } else {
            Response::AccessDenied("Unsuccessful login: E-mail does not exist");
        }
    }


    public static function AllUsers()
    {
        $result = self::$service->all();
        Response::Ok($result, "All users");
    }

    public static function SaveUser()
    {
        // $result = self::createUser();
        // $result = self::update();
        Response::Ok(null, "Saved user");
    }
}
