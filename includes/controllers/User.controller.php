<?php
final class UserController extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new UserService();
    }

    public static function New()
    {
        return new self;
    }

    public function Login()
    {
        $data = json_decode(file_get_contents("php://input"));

        $emailExists = $this->service->emailExists($data->email);
        if ($emailExists) {
            $user = $this->service->getByEmail($data->email);
            MiddleWare::VerifyPassword($data, $user);
        } else {
            Response::AccessDenied("Unsuccessful login: E-mail does not exist");
        }
    }

    public function AllUsers()
    {
        $result = $this->service->all();
        Response::Ok($result, "All users");
    }

    public function NewUser()
    {
        $data = json_decode(file_get_contents("php://input"));
        $user = new AppUser(
            0,
            $data->firstname,
            $data->lastname,
            $data->email,
            $data->password
        );
        if ($this->service->emailExists($user->email)) {
            Response::Warning("E-mail is already in use");
        } else {
            $result = $this->service->create($user);
            Response::Ok($result, "Profile created");
        }
    }

    public function UpdateUserinfo()
    {
        $data = json_decode(file_get_contents("php://input"));
        $user = new UpdateUserinfo(
            $data->id,
            $data->firstname,
            $data->lastname,
            $data->email
        );
        $foundUser = $this->service->find($user->id);
        if ($this->service->emailExists($user->email) && $user->email !== $foundUser->email) {
            Response::Warning("E-mail is already in use");
        } else {
            $result = $this->service->update($user);
            Response::Ok($result, "Updated profile info");
        }
    }
}
