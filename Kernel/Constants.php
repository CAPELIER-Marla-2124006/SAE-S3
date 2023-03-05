<?php
final class Constants{

    public static function rootDir(): false|string
	{
        return realpath(__DIR__ . '/../');
    }

    public static function viewsDir(): string
	{
        return self::rootDir().'/Views/';
    }

}
