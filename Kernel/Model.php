<?php

final class Model
{
	private static ?PDO $adminConn = null;
	private static ?PDO $userConn = null;

	public static function getUserConnexion(): PDO
	{
		if(self::$userConn === null)
			self::$userConn = self::init('user');

		return self::$userConn;
	}

	public static function getAdminConnexion(): PDO
	{
		if(self::$adminConn === null)
			self::$adminConn = self::init('admin');

		return self::$adminConn;
	}

	private static function init(string $which): PDO
	{
		$db_username = $_ENV["DB_USERNAME"];
		$db_password = $_ENV["DB_PASSWORD"];

		if($which === 'user') {
			$db_username = 'IUT-SAE-USER';
			$db_password = 'iut-sae-user';
		}

		$PDO_URI = sprintf("mysql:host=%s;dbname=%s", $_ENV["DB_HOST"], $_ENV["DB_DBNAME"]);

		try{
			return new PDO($PDO_URI, $db_username, $db_password);
		}catch(PDOException $e){
			throw new HTTPSpecialCaseException(500, "Connection to the database failed");
		}
	}
}
