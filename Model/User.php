<?php

class User {

	private string $username, $password, $notes;
	private int $level, $colorHue, $points;

	public function __construct(string $username, string $password, string $notes, int $level, int $colorHue, int $points) {
		$this->username = $username;
		$this->password = $password;
		$this->notes = $notes;
		$this->level = $level;
		$this->colorHue = $colorHue;
		$this->points = $points;
	}

	/**
	 * Username getter
	 * @return string The username of the user
	 */
	public function getUsername(): string {
		return $this->username;
	}

	/**
	 * Password getter
	 * @return string The password of the user
	 */
	public function getPassword(): string {
		return $this->password;
	}

	/**
	 * Password setter
	 * @param string $password The new password
	 */
	public function setPassword(string $password): void {
		$this->password = $password;
	}

	/**
	 * Notes getter
	 * @return string The notes of the user
	 */
	public function getNotes(): string {
		return $this->notes;
	}

	/**
	 * Notes setter
	 * @param string $notes The new notes
	 */
	public function setNotes(string $notes): void {
		$this->notes = $notes;
	}

	/**
	 * Level getter
	 * @return int The level of the user
	 */
	public function getLevel(): int {
		return $this->level;
	}

	/**
	 * Level setter
	 * @param int $level The new level
	 */
	public function setLevel(int $level): void {
		$this->level = $level;
	}

	/**
	 * Hue getter
	 * @return int The hue of the user
	 */
	public function getColorHue(): int {
		return $this->colorHue;
	}

	/**
	 * Hue setter
	 * @param int $colorHue The new hue
	 */
	public function setColorHue(int $colorHue): void {
		$this->colorHue = $colorHue;
	}

	/**
	 * Points getter
	 * @return int The points of the user
	 */
	public function getPoints(): int {
		return $this->points;
	}

	/**
	 * Ponts setter
	 * @param int $points The new points
	 */
	public function setPoints(int $points): void {
		$this->points = $points;
	}

}
