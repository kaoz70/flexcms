<?php

namespace App;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/25/15
 * Time: 10:17 AM
 */
class Requirements
{

    static function check()
    {
        self::php("5.4.0");
        self::mod_rewrite();
        self::pdo();
    }

    /**
     * Check if the provided PHP version is the correct one
     *
     * @param $version
     */
    private static function php($version)
    {
        if (!version_compare($version, PHP_VERSION, '<=')) {
            Error::exception("The provided PHP version should be higher than: <strong>" . $version . "</strong>. Current version is: <strong>" . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . "." . PHP_RELEASE_VERSION . "</strong>");
        }
    }

    /**
     * Check if mod_rewrite is enabled in the server, if its Apache, this provides us with SEO friendly URLs
     */
    private static function mod_rewrite()
    {
        if (function_exists('apache_get_modules') && !array_key_exists('HTTP_MOD_REWRITE', $_SERVER)) {
            Error::exception("Apache module <strong>mod_rewrite</strong> is not available or is not enabled.");
        }
    }

    /**
     * Check if PDO class is available, we need this because of Laravel's database connection and Models
     */
    private static function pdo()
    {
        if (!extension_loaded('pdo')) {
            Error::exception("PHP extension <strong>PDO</strong> is not available or is not enabled.");
        }
    }

}