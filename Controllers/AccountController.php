<?php

class AccountController extends AController
{

	public function process(){
		$data = new DataAccess(Model::getAdminConnexion());
		switch ($this->urlFolder) {
			case 'login':{
				$username = $this->postParams['username'];
				$password = $this->postParams['password'];

				$user = $data->getUser($username);
				if($user == null) {
					header("Location: /game?loginError=username");
					die();
				}

				if(password_verify($password, $user->getPassword())) {
					Session::start_session(3 * 60 * 60);
					Session::set_login($username);
					header("Location: /game");
				} else {
					header("Location: /game?loginError=password");
				}

				break;
			}
			case 'register':{
				$username = $this->postParams['username'];
				$password = $this->postParams['password'];
				$confirm_password = $this->postParams['confirm-password'];

				if(empty($username) || empty($password)) {
					header("Location: /game?registerError=empty");
					die();
				} elseif($password != $confirm_password) {
					header("Location: /game?registerError=password_confirm");
					die();
				} elseif($data->getUser($username) != null) {
					header("Location: /game?registerError=username_exist");
					die();
				}

				$data->insertUser(
					new User($username,
						password_hash($password, PASSWORD_BCRYPT),
						"Notes pour plus tard",
						0, 164, 0
					)
				);

				Session::start_session(3*60*60);
				Session::set_login($username);

				break;
			}
			case 'disconnect':{
				break;
			}
		}
	}
}