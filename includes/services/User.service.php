<?php

/**
 * UserService for login user in session, checking if user is authenticated and to get an instance of current user as AppUser
 *
 * @version 2.0
 * @author Mick
 */
final class UserService
{
	private $repository;

	/**
	 * @param UserRepository $repository
	 */
	function __construct(UserRepository $repository = null)
	{
		if ($repository == null)
			$repository = new UserRepository();

		$this->repository = $repository;
	}

	/**
	 * @return AppUser[]
	 */
	public function all()
	{ 
		return $this->repository->all();
	}

	public function emailExists(string $email)
    {
        return $this->repository->emailExists($email);
	}
	
	public function getByEmail(string $email)
    {
        return $this->repository->getByEmail($email);
    }

	// Allt här under kan tas bort


	//public static function saltPassword($user, $pass)
	//{
	//    $user_salt = sha1($user);
	//    $password = md5($user_salt . $pass . 'Z1Jr-y$SIq7IQ/0Q^`V**7;Tf@o4Q{XYwCSI;,biW:wB~H]_@ySiMpr_TlLEO?ml');
	//    return $password;
	//}

	/**
	 * Returns true if user is logged in and authenticated, else false
	 * @return boolean
	 */
	public function authenticatedUser()
	{
		//Todo: implement $sessionService
		$authFlag = (
				(isset($_SESSION[self::Userid]) && $_SESSION[self::Userid] > 0) && (isset($_SESSION[self::Username]) && is_string($_SESSION[self::Username]) == true) && (isset($_SESSION[self::LoggedInFlag]) && $_SESSION[self::LoggedInFlag] == true));
		//Todo: Also check stored sessionid and/or username/userid in db
		return $authFlag;
	}

	/**
	 * Returns current user if logged in else returns guest user
	 * @return AppUser|null|string
	 */
	public function currentUser()
	{
		if (self::authenticatedUser()) {
			$currentUser = $this->userRepository->find($this->sessionService->{self::Userid});
			if ($currentUser instanceof AppUser) {
				return $currentUser;
			}
			call(UserController, UserController::LOGINVIEW);
		}
		$anonymousUser = new AppUser(0, 'Guest', '', null, null, null);
		$this->sessionService->{self::Username} == $anonymousUser->name;
		$this->sessionService->{self::LoggedInFlag} == false;
		return $anonymousUser;
	}

	/**
	 * Logout user from session and db
	 * @param AppUser $user
	 * @return boolean
	 */
	public function logoutUser(AppUser $user)
	{
		//$this->userRepository->setUserAsLoggedOut($user);
		return $this->sessionService->destroy();
	}

	/**
	 * Login user in db and new session
	 * @param AppUser $user
	 * @return boolean
	 */
	public function loginUser(AppUser $user)
	{
		if ($user->id > 0 == false) {
			return false;
		}

		$currentUser = $this->userRepository->find($user->id);

		if ($currentUser instanceof AppUser) {
			if (isset($_SESSION)) {
				$this->sessionService->{self::Userid} = $currentUser->id;
				$this->sessionService->{self::Username} = $currentUser->name;
				$this->sessionService->{self::LoggedInFlag} = true;
			} else {
				throw new Exception('Session has not started');
			}
		}
		return false;
	}

	public function validateNewUser($username, $userpassword, $useremail, $useremailConfirmation)
	{
		$response = true;

		if ($username == null || $username == "") {
			if ($response instanceof ResponseResource == false) {
				$response = new ResponseResource(ResponseResource::ERROR);
			}
			$response->addMessage(new MessageResource('Username is required, moron...'));
		}

		if ($useremail == null || $useremail == "" || $useremailConfirmation == null || $useremailConfirmation == "") {
			if ($response instanceof ResponseResource == false) {
				$response = new ResponseResource(ResponseResource::ERROR);
			}
			$response->addMessage(new MessageResource('Email is required, moron...'));
		}


		if ($userpassword == null || $userpassword == "") {
			if ($response instanceof ResponseResource == false) {
				$response = new ResponseResource(ResponseResource::ERROR);
			}
			$response->addMessage(new MessageResource('Password is required, moron..'));
		}

		if (($useremail !== $useremailConfirmation) && ($useremail != null && $useremail != "" && $useremailConfirmation != null && $useremailConfirmation != "")) {
			if ($response instanceof ResponseResource == false) {
				$response = new ResponseResource(ResponseResource::ERROR);
			}
			$response->addMessage(new MessageResource('Emails does not match'));
		}

		if ($this->usernameExists($username) && $this->useremailExists($useremail)) {
			if ($response instanceof ResponseResource == false) {
				$response = new ResponseResource(ResponseResource::ERROR);
			}
			$response->addMessage(new MessageResource('Username and email is already taken'));
		} else {
			if ($this->usernameExists($username)) {
				if ($response instanceof ResponseResource == false) {
					$response = new ResponseResource(ResponseResource::ERROR);
				}
				$response->addMessage(new MessageResource('Username already taken'));
			}

			if ($this->useremailExists($useremail)) {
				if ($response instanceof ResponseResource == false) {
					$response = new ResponseResource(ResponseResource::ERROR);
				}
				$response->addMessage(new MessageResource('Email already in use by other user'));
			}
		}
		return ($response instanceof ResponseResource) ? $response : true;
	}

	public function usernameExists($username)
	{
		return $this->userRepository->usernameExists($username);
	}

	public function useremailExists($email)
	{
		return 	$this->userRepository->useremailExists($email);
	}

	public function createUser(AppUser $user)
	{
		return $this->userRepository->create($user);
	}
}
