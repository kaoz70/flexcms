<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 12/1/15
 * Time: 1:48 PM
 */

namespace App;


class Admin {

    const PATH = 'modules/';

    /**
     * Get the module configuration files
     *
     * @return array
     */
    static function getModules()
    {
        $items = [];

        //Get menu item data
        foreach (Utils::getFolders(APPPATH . static::PATH) as $folder) {
            $config = json_decode(file_get_contents(APPPATH . static::PATH . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'config.json'));
            $config->menu->controller = $folder;
            if($config->menu->show) {
                $items[$folder] = $config->menu;
            }
        }

        return $items;

    }

    /**
     * Get all the JS provided for each module
     *
     * @return array
     */
    static function getModuleAssets()
    {

        $assets = [];

        //Iterate over each module folder
        foreach (Utils::getFolders(APPPATH . static::PATH) as $folder) {

            $path = APPPATH . static::PATH . $folder . DIRECTORY_SEPARATOR . 'js';

            //If the JS does'nt exist continue over the next module folder
            if(!file_exists($path)) {
                continue;
            }

            //Find any assets in the JS folder
            foreach (new \DirectoryIterator($path) as $file) {

                //Ignore . (dot) files: . or ..
                if ($file->isDot()) {
                    continue;
                }

                //Get any files in this dir
                if ($file->isDir() ) {

                    $assetFolder = $path . DIRECTORY_SEPARATOR . $file;

                    //Iterate over every file
                    foreach (new \DirectoryIterator($assetFolder) as $f) {
                        if($f->isFile()) {
                            $assets[] = $assetFolder . DIRECTORY_SEPARATOR . $f->getFilename();
                        }
                    }
                }

                if($file->isFile()) {
                    $assets[] = $path . DIRECTORY_SEPARATOR . $file->getFilename();
                }

            }

        }

        return $assets;

    }

    /**
     * Get any content modules
     *
     * @return array
     */
    static function getContentModules()
    {

        $items = [];

        //Get menu item data
        foreach (Utils::getFolders(APPPATH . static::PATH) as $folder) {
            $config = json_decode(file_get_contents(APPPATH . static::PATH . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'config.json'));
            if(isset($config->content) && $config->content) {
                $items[$folder] = $config;
            }
        }

        return $items;
    }

}