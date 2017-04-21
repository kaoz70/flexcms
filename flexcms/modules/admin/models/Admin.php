<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 12/1/15
 * Time: 1:48 PM
 */

namespace App;


class Admin {

    const PATH = 'modules' . DIRECTORY_SEPARATOR;

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
            $files = [];
            $folders = [];

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
                    $folders[] = $assetFolder;


                }

                if($file->isFile()) {
                    $files[] = $path . DIRECTORY_SEPARATOR . $file->getFilename();
                }

            }

            sort($folders);

            foreach ($folders as $folder) {
                //Iterate over every file
                foreach (new \DirectoryIterator($folder) as $f) {
                    if($f->isFile()) {
                        $files[] = $folder . DIRECTORY_SEPARATOR . $f->getFilename();
                    }
                }
            }

            $assets = array_merge($assets, $files);

        }

        sort($assets);

        return $assets;

    }

    /**
     * Get all the JS provided for each module
     *
     * @return array
     */
    static function getModuleViewPaths()
    {

        $folders = [];

        //Iterate over each module folder
        foreach (Utils::getFolders(APPPATH . static::PATH) as $folder) {
            $folders[] = APPPATH . static::PATH . $folder . DIRECTORY_SEPARATOR . 'views';
        }

        return $folders;

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
            if(isset($config->content) && $config->content === true) {
                $items[$folder] = $config;
            }
        }

        return $items;
    }

}