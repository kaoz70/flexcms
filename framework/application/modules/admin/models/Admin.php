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

    static private function getFolders()
    {
        $folders = [];
        foreach (new \DirectoryIterator(APPPATH . static::PATH) as $file) {
            if ($file->isDot()) continue;
            if ($file->isDir()) {
                $folders[] = $file->getFilename();
            }
        }

        return $folders;

    }

    static function getModules()
    {
        $items = [];

        //Get menu item data
        foreach (static::getFolders() as $folder) {
            $config = json_decode(file_get_contents(APPPATH . static::PATH . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'config.json'));
            $config->menu->controller = $folder;
            if($config->menu->show) {
                $items[$folder] = $config->menu;
            }
        }

        return $items;

    }

    static function getContentModules()
    {

        $items = [];

        //Get menu item data
        foreach (static::getFolders() as $folder) {
            $config = json_decode(file_get_contents(APPPATH . static::PATH . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'config.json'));
            if(isset($config->content) && $config->content) {
                $items[$folder] = $config;
            }
        }

        return $items;
    }

}