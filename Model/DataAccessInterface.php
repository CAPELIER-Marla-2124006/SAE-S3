<?php

interface DataAccessInterface
{

    /**
     * Get an exercise by its id in the database
     * @param int $id The id of the exercise
     * @return Exercise An instance that holds exercise attributes
     */
    public function getExercise(int $id): Exercise|null;

    /**
     * Get an user by its username in the database
     * @param string $username The username of the user
     * @return User An instance that holds user attributes
     */
    public function getUser(string $username): User|null;

    /**
     * Change a username in database and return a new user with its values
     * @param User $user The model to take values
     * @param string $username The new username
     * @return User A new user holding new data
     */
    public function updateUsername(User $user, string $username): User|null;

    /**
     * Update an user in the database
     * @param User $user The model to take values
     */
    public function updateUser(User $user): void;

    /**
     * Insert an user in the database
     * @param User $user The model to take values
     */
    public function insertUser(User $user): void;

    /**
     * Execute the request given by the user and return an array of Exercise instances (the result of the request)
     * @return array An array of Exercise instances
     */
    public function executeExerciseAnswer(string $request): array;

}