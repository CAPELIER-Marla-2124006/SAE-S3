<?php

class GameController
{
	private array $A_GameInfo;
    public function __construct() {
		$this->A_GameInfo = [
			'colorHue'=>360,
			'levels'=>1,
			'maxLevels'=>10,
			'notes'=>'Notes pour plus tard',
			'code'=>'SELECT * FROM test'
		];
    }

	public function display(): void
	{
		View::show('game', $this->A_GameInfo);
	}

}
