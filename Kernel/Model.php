<?php

final class Model
{
	private static PDO $conn;

	public static function get(): PDO
	{
		if(self::$conn === null){
			self::init();
		}
		return self::$conn;
	}

	private static function init(): void
	{
		$PDO_URI = sprintf("mysql:host=%s;dbname=%s", $_ENV["DB_HOST"], $_ENV["DB_DBNAME"]);

		try{
			self::$conn = new PDO($PDO_URI, $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"]);
		}catch(PDOException $e){
			throw new HTTPSpecialCaseException(500, "Connection to the database failed");
		}
	}
}