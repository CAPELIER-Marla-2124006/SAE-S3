<?php

interface DataAccessInterface
{

    /**
     * Get an exercise by its id in the database
     * @param int $id The id of the exercise
     * @return Exercise An instance that holds exercise attributes
     */
    public function getExercise(int $id): Exercise;

    /**
     * Check if an user exists in the database
     * @param string $username The username to check
     * @param string $password The password to check
     * @return bool Returns true if the pair (username, password) exists in the
     * USER table (false in the other case)
     */
    public function isUser(string $username, string $password): bool;

    /**
     * Get an user by its username in the database
     * @param string $username The username of the user
     * @return User An instance that holds user attributes
     */
    public function getUser(string $username): User;

    /**
     * Change a username in database and return a new user with its values
     * @param User $user The model to take values
     * @param string $username The new username
     * @return User A new user holding new data
     */
    public function updateUsername(User $user, string $username): User;

    /**
     * Update an user in the database
     * @param User $user The model to take values
     */
    public function updateUser(User $user): void;

}