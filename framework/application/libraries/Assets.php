<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

use Assetic\FilterManager;
use Assetic\Filter\ScssphpFilter;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\LessphpFilter;
use Assetic\Filter\JSMinFilter;

use Assetic\AssetWriter;
use Assetic\Asset\AssetCache;
use Assetic\Cache\FilesystemCache;

use Assetic\Factory\AssetFactory;

use Assetic\Asset\FileAsset;
use Assetic\AssetManager;

class Assets {

	//Path to where generated assets go
	static $asset_path = 'assets/cache/';

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

		if($theme) self::$asset_path = 'themes/' . $theme . '/cache/';
		$writer = new AssetWriter(self::$asset_path);

		//Generate CSS and minify each file
		if(ENVIRONMENT === 'production' OR ENVIRONMENT === 'testing') {

			$fm = new FilterManager();
			$fm->set('css_min', new CssMinFilter());
			$fm->set('scss', new ScssphpFilter());
			$fm->set('less', new LessphpFilter());

			$factory = self::getAssetFactory($fm);

			$asset = $factory->createAsset($files, array(
				'?css_min',
				'scss',
				'less'
			), array('output' => $prefix . '.*.css'));

			$cache = self::getAssetCache($asset);

            $asset_path = base_url() . self::$asset_path . $cache->getTargetPath();

            if(!file_exists($asset_path)) {
                $writer->writeAsset($cache);
            }

			echo '<link href="' . $asset_path . '" rel="stylesheet">';

		}

		//Return CSS and individual files if in DEVELOPMENT
		else {

			$paths = array();

			foreach ($files as $key => $file) {

				$fileinfo = pathinfo($file);

				$asset = new FileAsset($file, array(new ScssphpFilter(), new LessphpFilter()));

				$scripts = new AssetManager();
				$asset->setTargetPath('dev.' . $fileinfo['filename'] . '.css');
				$scripts->set($key, $asset);

				$cache = self::getAssetCache($asset);

				if(ENVIRONMENT !== 'developent') {
					$writer->writeAsset($cache);
				}

				$paths[] = $asset->getTargetPath();

			}

			//Remove duplicates and print links
			foreach (array_unique($paths) as $path) {
				echo '<link href="' . base_url() . self::$asset_path . $path . '" rel="stylesheet">' . PHP_EOL;
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

		if($theme) self::$asset_path = 'themes/' . $theme . '/cache/';
		$writer = new AssetWriter(self::$asset_path);

		//Concatenate JS and minify each file
		if(ENVIRONMENT === 'production' OR ENVIRONMENT === 'testing') {

			$fm = new FilterManager();
			$fm->set('js_min', new JSMinFilter());

			$factory = self::getAssetFactory($fm);
			$asset = $factory->createAsset($files, array(
				'?js_min',
			), array('output' => $prefix . '.*.js'));

			$cache = self::getAssetCache($asset);

            $asset_path = base_url() . self::$asset_path . $cache->getTargetPath();

            if(!file_exists($asset_path)) {
                $writer->writeAsset($cache);
            }

			echo '<script src="' . $asset_path . '" ></script>';
		}

		else {

			foreach ($files as $file) {
				echo '<script src="' . base_url() . $file . '" ></script>' . PHP_EOL;
			}

		}

		$CI->benchmark->mark($prefix . '_js_end');

	}

	private static function getAssetFactory($fm)
	{
		$factory = new AssetFactory('./');
		$factory->setFilterManager($fm);
		if(ENVIRONMENT !== 'production' AND ENVIRONMENT !== 'testing') {
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
	
}


/* End of file assets.php */