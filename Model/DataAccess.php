<?php

class DataAccess implements DataAccessInterface {

	private $data;

	private $prepStmtGetExercise;
	private $prepStmtIsUser;
	private $prepStmtGetUser;
	private $prepStmtUpdateUser;

	public function __construct(Pdo $data) {
		$this->data = $data;
		$this->prepareStatements();
	}

	/**
	 * Prepare all statements needed for the other abstract methods
	 * The function is only called by the constructor of the abstract class
	 */
	private function prepareStatements(): void {
		$this->prepStmtGetExercise = $this->data->prepare('SELECT * FROM EXERCISE WHERE ID=:id');
		$this->prepStmtIsUser = $this->data->prepare('SELECT * FROM USER WHERE USERNAME=:username AND PASSWORD=:password');
		$this->prepStmtGetUser = $this->data->prepare('SELECT * FROM USER WHERE USERNAME=:username');
		$this->prepStmtUpdateUser = $this->data->prepare('UPDATE USER SET PASSWORD=:password, NOTES=:notes, LEVEL=:level, COLOR_HUE=:hue, POINTS=:pts WHERE USERNAME=:username');
	}

	/**
	 * Get an exercise by its id in the database
	 * @param int $id The id of the exercise
	 * @return Exercise An instance that holds exercise attributes
	 */
	public function getExercise(int $id): Exercise {
		$this->prepStmtGetExercise->execute(['id' => $id]);
		$row = $this->prepStmtGetExercise->fetch();
		return new Exercise(
			$row['ID'], $row['NAME'], $row['CODE_INIT'], $row['LESSON'],
			$row['HINT'], $row['SUCCESS'], $row['ANSWER'], $row['POINTS']
		);
	}

	/**
	 * Check if an user exists in the database
	 * @param string $username The username to check
	 * @param string $password The password to check
	 * @return bool Returns true if the pair (username, password) exists in the
	 * USER table (false in the other case)
	 */
	public function isUser(string $username, string $password): bool {
		$this->prepStmtIsUser->execute(['username' => $username, 'password' => $password]);
		return $this->prepStmtIsUser->rowCount() != 0;
	}

	/**
	 * Get an user by its username in the database
	 * @param string $username The username of the user
	 * @return User An instance that holds user attributes
	 */
	public function getUser(string $username): User {
		$this->prepStmtGetUser->execute(['username' => $username]);
		$row = $this->prepStmtGetUser->fetch();
		return new User($row['USERNAME'], $row['PASSWORD'], $row['NOTES'],
			$row['LEVEL'], $row['COLOR_HUE'], $row['POINTS']);
	}

	/**
	 * Update an user in the database
	 * @param User $user The model to take values
	 */
	public function updateUser(User $user): void {
		$this->prepStmtUpdateUser->execute([
			'username' => $user->getUsername(),
			'password' => $user->getPassword(),
			'notes' => $user->getNotes(),
			'level' => $user->getLevel(),
			'hue' => $user->getColorHue(),
			'points' => $user->getPoints()
		]);
	}

}