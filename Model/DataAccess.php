<?php

class DataAccess extends ADataAccess {
	private $prepReqGetExercise;
	private $prepReqIsUser;
	private $prepReqGetUser;
	private $prepReqUpdateUser;

	public function __construct(PDO $data) {
		parent::__construct($data);
	}

	/**
	 * Prepare all statements needed for the other abstract methods
	 * The function is only called by the constructor of the abstract class
	 */
	public function prepareStatements(): void {
		$this->prepReqGetExercise = $this->data->prepare('SELECT * FROM EXERCISE WHERE ID=:id');
		$this->prepReqIsUser = $this->data->prepare('SELECT * FROM USER WHERE USERNAME=:username AND PASSWORD=:password');
		$this->prepReqGetUser = $this->data->prepare('SELECT * FROM USER WHERE USERNAME=:username');
		$this->prepReqUpdateUser = $this->data->prepare('UPDATE USER SET PASSWORD=:password, NOTES=:notes, LEVEL=:level, COLOR_HUE=:hue, POINTS=:pts WHERE USERNAME=:username');
	}

	/**
	 * Get an exercise by its id in the database
	 * @param int $id The id of the exercise
	 * @return Exercise An instance that holds exercise attributes
	 */
	public function getExercise(int $id): Exercise {
		$result = $this->prepReqGetExercise->execute(['id' => $id])->fetch();
		$row = $result->fetch();
		$exercise = new Exercise(
			$row['ID'], $row['NAME'], $row['CODE_INIT'], $row['LESSON'],
			$row['HINT'], $row['SUCCESS'], $row['ANSWER'], $row['POINTS']
		);
		$row->closeCursor();
		return $exercise;
	}

	/**
	 * Check if an user exists in the database
	 * @param string $username The username to check
	 * @param string $password The password to check
	 * @return bool Returns true if the pair (username, password) exists in the
	 * USER table
	 */
	public function isUser(string $username, string $password): bool {
		$result = $this->prepReqIsUser->execute(['username' => $username,
			'password' => $password]);
		$isUser = $result->rowCount() != 0;
		$result->closeCursor();
		return $isUser;
	}

	/**
	 * Get an user by its username in the database
	 * @param string $username The username of the user
	 * @return User An instance that holds user attributes
	 */
	public function getUser(string $username): User {
		$result = $this->prepReqGetUser->execute(['username' => $username]);
		$row = $result->fetch();
		$user = new User($row['USERNAME'], $row['PASSWORD'], $row['NOTES'],
			$row['LEVEL'], $row['COLOR_HUE'], $row['POINTS'], );
		$result->closeCursor();
		return $user;
	}

	/**
	 * Update an user in the database
	 * @param User $user The model to take values
	 */
	public function updateUser(User $user): void {
		$this->prepReqUpdateUser->execute([
			'username' => $user->getUsername(),
			'password' => $user->getPassword(),
			'notes' => $user->getNotes(),
			'level' => $user->getLevel(),
			'hue' => $user->getColorHue(),
			'points' => $user->getPoints()
		]);
	}

}