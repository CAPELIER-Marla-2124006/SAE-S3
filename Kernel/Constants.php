<?php
final class Constants{

    public static function rootDir() {
        return realpath(__DIR__ . '/../');
    }

    public static function viewsDir() {
        return self::rootDir().'/Views/';
    }

}
