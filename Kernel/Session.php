<?php

final class Session
{
	/**
	 * start a php session
	 * @return void
	 */
	public static function start_session(int $time): void
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			ini_set('session.gc_maxlifetime', $time);
			session_set_cookie_params($time);
			session_start();
		}
	}

	/**
	 * resume the session if possible
	 * @return bool
	 */
	public static function resume_session(): bool
	{
		if(self::has_session_cookie()){
			self::start_session(60*60);
			return true;
		}
		return false;
	}

	/**
	 * destroy the session
	 * @return void
	 */
	public static function destroy_session(): void
	{
		self::start_session(60*60);
		session_destroy();
	}

	/*
		Reason: start_session() automatically sets a cookie,
		we want a way to know if the user have a session without setting a cookie
		(e.g. to not set a cookie on every page to set the header, which change if you are logged-in)
	*/
	public static function has_session_cookie(): bool
	{
		return isset($_COOKIE[session_name()]);
	}

	/**
	 * Check if the user is logged in
	 * @return bool
	 */
	public static function is_login(): bool
	{
		if (!self::resume_session()) {
			return false;
		}
		if (!isset($_SESSION)) {
			return false;
		}
		if (!isset($_SESSION["ID"])) {
			return false;
		}
		if(!isset($_SESSION["LOGIN-TIME"])) {
			return false;
		}
		return true;
	}

	/**
	 * Set vars in session
	 * @param int|string $id
	 * @return void
	 */
	public static function set_login(int|string $id): void
	{
		self::start_session(60*60);
		$_SESSION["ID"] = $id;
		$_SESSION["LOGIN-TIME"] = time();
	}

	/**
	 * Forces to be logged in or redirect to connexion page
	 * @return void
	 */
	public static function login_or_die(): void
	{
		if (!self::is_login()) {
			header("Location: /annonces/index.php");
			die;
		}
	}

}
