<?php

class DataAccess implements DataAccessInterface {

	private PDO $data;

	private PDOStatement $prepStmtGetExercise;
	private PDOStatement $prepStmtIsUser;
	private PDOStatement $prepStmtGetUser;
	private PDOStatement $prepStmtUpdateUsername;
	private PDOStatement $prepStmtUpdateUser;

	public function __construct(PDO $data) {
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
		$this->prepStmtUpdateUsername = $this->data->prepare('UPDATE USER SET USERNAME=? WHERE USERNAME=?');
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
     * Change a username in database and return a new user with its values
     * @param User $user The model to take values
     * @param string $username The new username
     * @return User A new user holding new data
     */
    public function updateUsername(User $user, string $username): User {
		$this->prepStmtUpdateUsername->execute([$username, $user->getUsername()]);
		return new User($username, $user->getPassword(), $user->getNotes(),
			$user->getLevel(), $user->getColorHue(), $user->getPoints());
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