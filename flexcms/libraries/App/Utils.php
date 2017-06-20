<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 06-Dec-16
 * Time: 04:15 PM
 */

namespace App;


class Utils
{

    /**
     * Get the folder names in the provided path
     * 
     * @param $path
     * @return array
     */
    public static function getFolders($path)
    {
        $folders = [];
        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot()) continue;
            if ($file->isDir()) {
                $folders[] = $file->getFilename();
            }
        }

        //Sort the folders alphabetically (me need admin first)
        // TODO: Make admin always first independently of folder name
        sort($folders);

        return $folders;
    }

    /**
     * Recursively create folders if they don't exist
     *
     * @param $path
     * @param int $permissions
     */
    public static function createFolder($path, $permissions = 0755)
    {

        if ( ! is_dir($path)) {
            $oldmask = umask(0);
            mkdir($path, $permissions, true);
            umask($oldmask);
        }

    }

    /**
     * Checks if the request is an ajax request
     *
     * @return bool
     */
    public static function isAjaxRequest()
    {

        $isAngular = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
        $acceptsJsonResponse = (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        return $isAngular || $acceptsJsonResponse;

    }

}