<?php
final class Constants{

    /**
     * Get the root directory of the project
     * @return false|string root directory of the project
     */
    public static function rootDir(): false|string
	{
        return realpath(__DIR__ . '/../');
    }

    /**
     * Get the views directory of the project
     * @return string views directory of the project
     */
    public static function viewsDir(): string
	{
        return self::rootDir().'/Views/';
    }

}
