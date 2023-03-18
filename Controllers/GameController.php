<?php

class GameController
{
	private array $A_GameInfo;
    public function __construct() {
		$data = new DataAccess(Model::getAdminConnexion()); // connect as Admin

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
				'lesson'=>$exercice->getLesson()
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
				'instructions'=>$exercice->getInstructions()
			];
		}

    }

	public function display(): void
	{
		View::show('game', $this->A_GameInfo);
	}

}
