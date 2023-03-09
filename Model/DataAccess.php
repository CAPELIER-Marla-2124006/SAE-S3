<?php

class DataAccess implements DataAccessInterface {

	private PDO $data;

	public function __construct(PDO $data) {
		$this->data = $data;
	}

	/**
	 * Get an exercise by its id in the database
	 * @param int $id The id of the exercise
	 * @return Exercise An instance that holds exercise attributes
	 */
	 public function getExercise(int $id): Exercise {
		$statement = $this->data->prepare('SELECT * FROM APP_EXERCISE WHERE ID=?');
		$statement->execute([$id]);
		$row = $statement->fetch();
		return new Exercise(
			$row['ID'], $row['CODE_INIT'], $row['DESCRIPTION'], $row['LESSON'],
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
		$statement = $this->data->prepare('SELECT * FROM APP_USER WHERE USERNAME=? AND PASSWORD=?');
		$statement->execute([$username, $password]);
		return $statement->rowCount() != 0;
	}

	/**
	 * Get an user by its username in the database
	 * @param string $username The username of the user
	 * @return User An instance that holds user attributes
	 */
	public function getUser(string $username): User {
		$statement = $this->data->prepare('SELECT * FROM APP_USER WHERE USERNAME=?');
		$statement->execute([$username]);
		$row = $statement->fetch();
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
		$this->data->prepare('UPDATE APP_USER SET USERNAME=? WHERE USERNAME=?')->execute([$username, $user->getUsername()]);
		return new User($username, $user->getPassword(), $user->getNotes(),
			$user->getLevel(), $user->getColorHue(), $user->getPoints());
	}

	/**
	 * Update an user in the database
	 * @param User $user The model to take values
	 */
	public function updateUser(User $user): void {
		$this->data->prepare('UPDATE APP_USER SET PASSWORD=:password, NOTES=:notes, LEVEL=:level, COLOR_HUE=:hue, POINTS=:pts WHERE USERNAME=:username')->execute([
			'username' => $user->getUsername(),
			'password' => $user->getPassword(),
			'notes' => $user->getNotes(),
			'level' => $user->getLevel(),
			'hue' => $user->getColorHue(),
			'points' => $user->getPoints()
		]);
	}

}