<?php
/**
 * Class Minify_Loader
 * @package Minify
 */

/**
 * Class autoloader
 *
 * @package Minify
 * @author Stephen Clay <steve@mrclay.org>
 */
class Minify_Loader {
    public function loadClass($class)
    {
        $file = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
        $file .= strtr($class, "\\_", DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) . '.php';
        if (is_readable($file)) {
            require $file;
            return;
        }

        $map = array(
            'JavascriptPacker' => 'class.JavaScriptPacker.php',
        );

        if (!isset($map[$class])) {
            return;
        }

        @include $map[$class];
    }

    public static function register()
    {
        $inst = new self();
        spl_autoload_register(array($inst, 'loadClass'));
        return $inst;
    }
}

return Minify_Loader::register();
