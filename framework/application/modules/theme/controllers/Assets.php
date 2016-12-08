<?php

namespace theme;

use Assetic\Asset\AssetCache;
use Assetic\Asset\FileAsset;
use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\Cache\FilesystemCache;
use Assetic\Factory\AssetFactory;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSMinFilter;
use Assetic\Filter\LessphpFilter;
use Assetic\Filter\ScssphpFilter;
use Assetic\FilterManager;
use Exception;
use JSMin;
use RuntimeException;

$_ns = __NAMESPACE__;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assets {

    //Path to where generated assets go
    static $asset_path = 'assets' . DIRECTORY_SEPARATOR . 'cache';

    static $cache;

    static $writer;

    static $productionEnv = 'production';

    /**
     * Create the stylesheet tag
     * @param string $prefix
     * @param array $files
     * @param null $theme
     */
    static function css_group($prefix = '', $files = array(), $theme = NULL)
    {

        $CI =& get_instance();
        $CI->benchmark->mark($prefix . '_css_start');

        self::init($theme);

        //Generate CSS and minify each file
        if(ENVIRONMENT === static::$productionEnv) {

            try {

                $am = new AssetManager();

                $fm = new FilterManager();
                $fm->set('css_min', new CssMinFilter());
                $fm->set('scss', new ScssphpFilter());
                $fm->set('less', new LessphpFilter());

                $factory = self::getAssetFactory($am, $fm);

                $asset = $factory->createAsset($files, array(
                    '?css_min',
                    'scss',
                    'less'
                ), array('output' => $prefix . '.css'));

                self::$cache = self::getAssetCache($asset);
                $compiled_path = self::$asset_path . '/' . self::$cache->getTargetPath();
                $asset_path = base_url() . self::$asset_path . '/' . self::$cache->getTargetPath();

                self::writeFile($files, $compiled_path);

                echo '<link href="' . $asset_path . '" rel="stylesheet">';

            } catch(RuntimeException $e) {
                echo $e->getMessage();
            } catch(Exception $e) {
                echo $e->getMessage();
            }

        }

        //Return CSS and individual files if in DEVELOPMENT
        else {

            set_time_limit(0);
            $paths = array();

            try {
                foreach ($files as $key => $file) {

                    $fileinfo = pathinfo($file);

                    $asset = new FileAsset($file, [
                        new ScssphpFilter(),
                        new LessphpFilter()
                    ]);

                    $scripts = new AssetManager();
                    $asset->setTargetPath('dev.' . $fileinfo['filename'] . '.css');
                    $scripts->set($key, $asset);

                    self::$cache = self::getAssetCache($asset);

                    if(ENVIRONMENT !== 'developent') {
                        self::$writer->writeAsset(self::$cache);
                    }

                    $paths[] = $asset->getTargetPath();

                }

                //Remove duplicates and print links
                foreach (array_unique($paths) as $path) {
                    echo '<link href="' . base_url() . self::$asset_path . '/' . $path . '?' . time() . '" rel="stylesheet">' . PHP_EOL;
                }
            } catch (Exception $e) {
                show_error($e->getMessage(), 500);
            }

        }

        $CI->benchmark->mark($prefix . '_css_end');

    }

    /**
     * Create the javascript tag
     * @param string $prefix
     * @param array $files
     * @param null $theme
     */
    static function js_group($prefix = '', $files = array(), $theme = NULL)
    {

        $CI =& get_instance();
        $CI->benchmark->mark($prefix . '_js_start');

        self::init($theme);

        //Concatenate JS and minify each file
        if($prefix === 'admin' || ENVIRONMENT === static::$productionEnv) {

            try {

                $am = new AssetManager();

                $fm = new FilterManager();
                $fm->set('js_min', new JSMinFilter());

                $factory = self::getAssetFactory($am, $fm);
                $asset = $factory->createAsset($files, array(
                    '?js_min',
                ), array('output' => $prefix . '.js'));

                self::$cache = self::getAssetCache($asset);

                $compiled_path = self::$asset_path . DIRECTORY_SEPARATOR . self::$cache->getTargetPath();
                $asset_path = base_url() .$compiled_path;

                self::writeFile($files, $compiled_path);

                echo '<script src="' . $asset_path . '" ></script>';

            }  catch(RuntimeException $e) {
                echo $e->getMessage();
            } catch(Exception $e) {
                echo $e->getMessage();
            }

        }

        else {

            foreach ($files as $file) {
                echo '<script src="' . base_url() . $file . '?' . time() . '" ></script>' . PHP_EOL;
            }

        }

        $CI->benchmark->mark($prefix . '_js_end');

    }

    private static function getAssetFactory($am, $fm)
    {
        $factory = new AssetFactory('./');
        $factory->setAssetManager($am);
        $factory->setFilterManager($fm);

        if(ENVIRONMENT !== static::$productionEnv) {
            set_time_limit(0);
            $factory->setDebug(TRUE);
        }

        return $factory;
    }

    private static function getAssetCache($asset)
    {
        return new AssetCache(
            $asset,
            new FilesystemCache(APPPATH .'/cache/assetic/')
        );
    }

    private static function init($theme)
    {
        if($theme) {
            self::$asset_path = 'themes/' . $theme . '/cache';
        }
        self::$writer = new AssetWriter(self::$asset_path);
    }

    /**
     * Checks the modified time of all files and writes the final asset if one of them is modified
     *
     * @param $files
     * @param $compiled_path
     */
    private static function writeFile($files, $compiled_path)
    {
        //Check if files have benn modified
        $files = array_combine($files, array_map("filemtime", $files));
        arsort($files);
        $latest_file = key($files);

        //If files have been modified, write the new asset
        if( ! file_exists($compiled_path) || ($files[$latest_file] > filemtime($compiled_path))) {
            set_time_limit(0);
            self::$writer->writeAsset(self::$cache);
        }
    }

    var $m_config;

    public function slideshow_js()
    {

        $this->output->set_header('Content-Type: application/javascript');
        $banners = $this->Banners->getAll(FALSE);
        $js = '';

        foreach ($banners as $key => $banner){
            $this->load->set_theme($this->m_config->theme);
            $data['return'] =  $this->load->view('modulos/banners/' . $banner['bannerType'] . '/' . 'javascript', $banner, TRUE);
            $this->load->set_admin_theme();
            $js .= $this->load->view('admin/request/html', $data, TRUE);
        }

        if(ENVIRONMENT === static::$productionEnv) {
            $js = JSMin::minify($js);
        }

        $this->output->set_output($js);

    }


}