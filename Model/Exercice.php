<?php

namespace Model;

class Exercice
{
	private string $code_init, $instructions, $lesson, $hint, $success, $answer;
	private int $id, $points;

	public function __construct(int $id, string $code_init, string $instructions, string $lesson, string $hint,
								string $success, string $answer, int $points) {
		$this->id = $id;
		$this->code_init = $code_init;
		$this->instructions = $instructions;
		$this->lesson = $lesson;
		$this->hint = $hint;
		$this->success = $success;
		$this->answer = $answer;
		$this->points = $points;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getCodeInit(): string
	{
		return $this->code_init;
	}

	/**
	 * @param string $code_init
	 */
	public function setCodeInit(string $code_init): void
	{
		$this->code_init = $code_init;
	}

	/**
	 * @return string
	 */
	public function getInstructions(): string
	{
		return $this->instructions;
	}

	/**
	 * @param string $instructions
	 */
	public function setInstructions(string $instructions): void
	{
		$this->instructions = $instructions;
	}

	/**
	 * @return string
	 */
	public function getLesson(): string
	{
		return $this->lesson;
	}

	/**
	 * @param string $lesson
	 */
	public function setLesson(string $lesson): void
	{
		$this->lesson = $lesson;
	}

	/**
	 * @return string
	 */
	public function getHint(): string
	{
		return $this->hint;
	}

	/**
	 * @param string $hint
	 */
	public function setHint(string $hint): void
	{
		$this->hint = $hint;
	}

	/**
	 * @return string
	 */
	public function getSuccess(): string
	{
		return $this->success;
	}

	/**
	 * @param string $success
	 */
	public function setSuccess(string $success): void
	{
		$this->success = $success;
	}

	/**
	 * @return string
	 */
	public function getAnswer(): string
	{
		return $this->answer;
	}

	/**
	 * @param string $answer
	 */
	public function setAnswer(string $answer): void
	{
		$this->answer = $answer;
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