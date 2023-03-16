<?php

class Exercise
{
	private string $code_init, $instructions, $lesson, $hint, $success, $exexercise_answer;
	private int $id, $points;

	public function __construct(int $id, string $code_init, string $instructions, string $lesson, string $hint,
								string $success, string $exercise_answer, int $points) {
		$this->id = $id;
		$this->code_init = $code_init;
		$this->instructions = $instructions;
		$this->lesson = $lesson;
		$this->hint = $hint;
		$this->success = $success;
		$this->exercise_answer = $exercise_answer;
		$this->points = $points;
	}

	/**
	 * Id getter
	 * @return int The id of the exercise
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * CodeInit getter
	 * @return string The initial code of the exercise
	 */
	public function getCodeInit(): string
	{
		return $this->code_init;
	}

	/**
	 * CodeInit setter
	 * @param string $code_init The new initial code
	 */
	public function setCodeInit(string $code_init): void
	{
		$this->code_init = $code_init;
	}

	/**
	 * Instructions getter
	 * @return string The instructions of the exercise
	 */
	public function getInstructions(): string
	{
		return $this->instructions;
	}

	/**
	 * Instructions setter
	 * @param string $instructions The new instructions
	 */
	public function setInstructions(string $instructions): void
	{
		$this->instructions = $instructions;
	}

	/**
	 * Lesson getter
	 * @return string The lesson of the exercise
	 */
	public function getLesson(): string
	{
		return $this->lesson;
	}

	/**
	 * Lesson setter
	 * @param string $lesson The new lesson
	 */
	public function setLesson(string $lesson): void
	{
		$this->lesson = $lesson;
	}

	/**
	 * Hint getter
	 * @return string The hint of the exercise
	 */
	public function getHint(): string
	{
		return $this->hint;
	}

	/**
	 * Hint setter
	 * @param string $hint The new hint
	 */
	public function setHint(string $hint): void
	{
		$this->hint = $hint;
	}

	/**
	 * Success getter
	 * @return string The success of the exercise
	 */
	public function getSuccess(): string
	{
		return $this->success;
	}

	/**
	 * Success setter
	 * @param string $success The new success
	 */
	public function setSuccess(string $success): void
	{
		$this->success = $success;
	}

	/**
	 * Exercise_answer getter
	 * @return string The exercise_answer of the exercise
	 */
	public function getExercise_answer(): string
	{
		return $this->exercise_answer;
	}

	/**
	 * Exercise_answer setter
	 * @param string $exercise_answer The new exercise_answer
	 */
	public function setExercise_answer(string $exercise_answer): void
	{
		$this->exercise_answer = $exercise_answer;
	}

	/**
	 * Points getter
	 * @return int The points of the exercise
	 */
	public function getPoints(): int
	{
		return $this->points;
	}

	/**
	 * Ponts setter
	 * @param int $points The new points
	 */
	public function setPoints(int $points): void
	{
		$this->points = $points;
	}

}
