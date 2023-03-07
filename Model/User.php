<?php

namespace Model;

class User
{
	private string $username, $password, $notes;
	private int $id, $level, $colorHue, $points;

	public function __construct(int $id, string $username, string $password, string $notes,
								int $level, int $colorHue, int $points) {
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->notes = $notes;
		$this->level = $level;
		$this->colorHue = $colorHue;
		$this->points = $points;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getNotes(): string
	{
		return $this->notes;
	}

	/**
	 * @param string $notes
	 */
	public function setNotes(string $notes): void
	{
		$this->notes = $notes;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getLevel(): int
	{
		return $this->level;
	}

	/**
	 * @param int $level
	 */
	public function setLevel(int $level): void
	{
		$this->level = $level;
	}

	/**
	 * @return int
	 */
	public function getColorHue(): int
	{
		return $this->colorHue;
	}

	/**
	 * @param int $colorHue
	 */
	public function setColorHue(int $colorHue): void
	{
		$this->colorHue = $colorHue;
	}

	/**
	 * @return int
	 */
	public function getPoints(): int
	{
		return $this->points;
	}

	/**
	 * @param int $points
	 */
	public function setPoints(int $points): void
	{
		$this->points = $points;
	}



}