<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 12/3/15
 * Time: 4:26 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use stdClass;

class Slider extends Model {

    static public function getTypes()
    {
        $folders = array();
        $path = APPPATH . 'modules/slider/sliders/';

        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot()) continue;
            if ($file->isDir()) {
                $slider  = new stdClass();
                $slider->folder =  $file->getFilename();
                $slider->data =  json_decode(file_get_contents($path . $file->getFilename() . '/config.json'));
                $folders[] = $slider;
            }
        }

        return $folders;
    }

    public function store($post)
    {

        $config = NULL;

        //if config present
        if(array_key_exists($post['type'], $post['config'])) {
            $config = $post['config'][$post['type']];

            foreach ($config as $key => $item) {

                $config[$key] = $this->prepareVar($item);

                if(is_array($item)){
                    foreach($item as $key2 => $prop){
                        $config[$key][$key2] = $this->prepareVar($prop);
                    }
                }

            }
        }

        $this->name = $post['name'];
        $this->class = $post['class'];
        $this->type = $post['type'];
        $this->width = $post['width'];
        $this->height = $post['height'];
        $this->config = json_encode($config, JSON_PRETTY_PRINT);
        $this->enabled = isset($post['enabled']) ?: 0;
        $this->temporary = 0;

        $this->save();

    }

    /**
     * Prepare the var so its value are correctly stored in the JSON string
     *
     * @param $item
     * @return bool|mixed|null
     */
    private function prepareVar($item)
    {

        //Check if boolean
        if($item === "true" || $item === "false") {
            $item === "true" ? $item = true : $item;
            $item === "false" ? $item = false : $item;
            return $item;
        }

        //Check if null
        if($item === "null"){
            return null;
        }

        //Check if int
        $int = filter_var($item, FILTER_VALIDATE_INT);
        $int !== FALSE ? $item = $int : $item;

        //Check if float
        $float = filter_var($item, FILTER_VALIDATE_FLOAT);
        $float !== FALSE ? $item = $float : $item;

        return $item;
    }

}