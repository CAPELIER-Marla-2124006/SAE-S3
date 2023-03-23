<?php

class GameController
{
	private array $A_GameInfo;
    public function __construct(array $getParams) {
		$data = new DataAccess(Model::getAdminConnexion()); // connect as Admin

		$loginError = "";
		if(isset($getParams['loginError']))
		switch ($getParams['loginError']) {
			case 'username':{
				$loginError = "Cet utilisateur n'existe pas";
				break;
			}
			case 'password':{
				$loginError = "Mauvais mot de passe";
				break;
			}
		}

		$registerError = "";
		if(isset($getParams['registerError']))
		switch($getParams['registerError']) {
			case 'empty':{
				$registerError = "Le nom ou le mot de passe ne peut pas être vide";
				break;
			}
			case 'password_confirm':{
				$registerError = "Les mots de passe ne correspondent pas";
				break;
			}
			case 'username_exist':{
				$registerError = "Ce nom existe déjà";
				break;
			}
		}

		if(Session::is_login()) {
			$user = $data->getUser($_SESSION['ID']);
			$exercice = $data->getExercise($user->getLevel());

			$this->A_GameInfo = [
				'username'=>$user->getUsername(),
				'points'=>$user->getPoints(),
				'colorHue'=>$user->getColorHue(),
				'levels'=>$user->getLevel(),
				'maxLevels'=>8,
				'notes'=>$user->getNotes(),
				'code'=>$exercice->getCodeInit(),
				'hint'=>$exercice->getHint(),
				'lesson'=>$exercice->getLesson(),
				'instructions'=>$exercice->getInstructions(),
				'loginError'=>$loginError,
				'registerError'=>$registerError
			];

		} else {
			$exercice = $data->getExercise(1);
			$this->A_GameInfo = [
				'colorHue'=>164,
				'levels'=>1,
				'maxLevels'=>8,
				'notes'=>'Notes pour plus tard',
				'code'=>$exercice->getCodeInit(),
				'hint'=>$exercice->getHint(),
				'lesson'=>$exercice->getLesson(),
				'instructions'=>$exercice->getInstructions(),
				'success'=>$exercice->getSuccess(),
				'loginError'=>$loginError,
				'registerError'=>$registerError
			];
		}

    }

	public function display(): void
	{
		View::show('game', $this->A_GameInfo);
	}

}
