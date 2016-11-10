<?php

/**
 * Part of the Themes package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Themes
 * @version    3.0.6
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2016, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Themes;

use RuntimeException;
use JsonSchema\Validator;
use Seld\JsonLint\JsonParser;
use UnexpectedValueException;
use Seld\JsonLint\ParsingException;
use Illuminate\Support\NamespacedItemResolver;

class Theme implements ThemeInterface
{
    /**
     * The shared theme bag instance.
     *
     * @var \Cartalyst\Themes\ThemeBag
     */
    protected $themeBag;

    /**
     * The theme path.
     *
     * @var string
     */
    protected $path;

    /**
     * The identifier of the theme within it's respective area.
     *
     * @var string
     */
    protected $key;

    /**
     * The area name which the theme belongs to.
     *
     * @var string
     */
    protected $area;

    /**
     * The theme's attributes.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Creates a new theme instance.
     *
     * @param  \Cartalyst\Themes\ThemeBag  $themeBag
     * @param  string  $path
     * @return void
     */
    public function __construct(ThemeBag $themeBag, $path)
    {
        $this->themeBag = $themeBag;

        $this->setPath($path);

        $this->setupThemeContext();
    }

    /**
     * Returns the theme slug, which is the area and the key of the theme.
     *
     * Format is as follows:
     *
     * - Theme in area (prepend the theme key with the area name and "::"):
     *    "my-area::vendor/theme"
     *
     * - Theme out of/without an area (matches the theme's key):
     *    "vendor/theme"
     *
     * @return string
     */
    public function getSlug()
    {
        return isset($this->area) ? "{$this->area}::{$this->key}" : $this->key;
    }

    /**
     * Returns the key for the theme, which is the unique
     * identifier for the theme within it's area.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the area for the theme, if any.
     *
     * @return string|null
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Get the fully qualified location of the view.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the parent slug for the theme.
     *
     * @return string
     */
    public function getParentSlug()
    {
        return $this->parent;
    }

    /**
     * Get the packages path for the theme.
     *
     * @param  string  $package
     * @return string
     */
    public function getPackagesPath($package = null)
    {
        $path = $this->path.'/'.$this->themeBag->getPackagesPath();

        if (! is_null($package)) {
            $path .= '/'.$package;
        }

        return $path;
    }

    /**
     * Get the namespaces path for the theme.
     *
     * @param  string  $namespace
     * @return string
     */
    public function getNamespacesPath($namespace = null)
    {
        $path = $this->path.'/'.$this->themeBag->getNamespacesPath();

        if (! is_null($namespace)) {
            $path .= '/'.$namespace;
        }

        return $path;
    }

    /**
     * Returns the path for a package.
     *
     * @param  string  $package
     * @return string
     */
    public function getPackagePath($package)
    {
        return $this->getPackagesPath($package);
    }

    /**
     * Returns the path for a namespace.
     *
     * @param  string  $namespace
     * @return string
     */
    public function getNamespacePath($namespace)
    {
        return $this->getNamespacePath($namespace);
    }

    /**
     * Returns the views path for the theme.
     *
     * @return string
     */
    public function getViewsPath()
    {
        return $this->path.'/'.$this->themeBag->getViewsPath();
    }

    /**
     * Returns the views path for a package.
     *
     * @param  string  $package
     * @return string
     */
    public function getPackageViewsPath($package)
    {
        return $this->getPackagesPath($package).'/'.$this->themeBag->getViewsPath();
    }

    /**
     * Returns the views path for a namespace.
     *
     * @param  string  $namespace
     * @return string
     */
    public function getNamespaceViewsPath($namespace)
    {
        return $this->getNamespacesPath($namespace).'/'.$this->themeBag->getViewsPath();
    }

    /**
     * Returns the assets path for the theme,
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->path.'/'.$this->themeBag->getAssetsPath();
    }

    /**
     * Returns the assets path for a package.
     *
     * @param  string  $package
     * @return string
     */
    public function getPackageAssetsPath($package)
    {
        return $this->getPackagesPath($package).'/'.$this->themeBag->getAssetsPath();
    }

    /**
     * Returns the views path for a namespace,
     *
     * @param  string  $namespace
     * @return string
     */
    public function getNamespaceAssetsPath($namespace)
    {
        return $this->getNamespacesPath($namespace).'/'.$this->themeBag->getAssetsPath();
    }

    /**
     * Sets up the theme's context.
     *
     * @return void
     */
    public function setupThemeContext()
    {
        $this->loadInfoFile();
    }

    /**
     * Sets the theme path. The path will be parsed using realpath if
     * possible so that no relative directories interferes with it.
     *
     * @param  string  $path
     * @return void
     */
    protected function setPath($path)
    {
        $this->path = realpath($path) ?: $path;
    }

    /**
     * Loads the Theme Info JSON file for the theme.
     *
     * @return void
     */
    protected function loadInfoFile()
    {
        $file = "{$this->path}/theme.json";

        $json = $this->themeBag->getFilesystem()->get($file);

        $data = json_decode($json);

        if (is_null($data) and ! is_null($json)) {
            $this->validateSyntax($json, $file);
        }

        $this->validateSchema($data, $file);

        $data = json_decode($json, true);

        $resolver = new NamespacedItemResolver;

        list($this->area, $this->key) = $resolver->parseKey($data['slug']);

        unset($data['slug']);

        $this->setAttributes($data);
    }

    /**
     * Validates the syntax of the theme Info JSON file.
     *
     * @param  string  $json
     * @param  string  $file
     * @return bool
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    protected function validateSyntax($json, $file = null)
    {
        $parser = new JsonParser;
        $result = $parser->lint($json);

        if (is_null($result)) {
            if (defined('JSON_ERROR_UTF8') and JSON_ERROR_UTF8 === json_last_error()) {
                throw new UnexpectedValueException("[{$file}] is not UTF-8, could not parse as JSON.");
            }

            return true;
        }

        throw new RuntimeException("[{$file}] does not contain valid JSON. {$result->getMessage()}.");
    }

    /**
     * Validates the schema of the theme Info JSON file according
     * to the schema specifications.
     *
     * @param  StdClass  $data
     * @param  string    $file
     * @return void
     * @throws \RuntimeException
     */
    protected function validateSchema($data, $file = null)
    {
        // Load up the schema file.
        $schemaFile = __DIR__.'/../resources/theme-schema.json';
        $schemaData = json_decode(file_get_contents($schemaFile));

        // Create a validator instance and check the
        $validator = new Validator;
        $validator->check($data, $schemaData);

        if (! $validator->isValid()) {
            $errors = array();

            foreach ($validator->getErrors() as $error) {
                $errors[] = ($error['property'] ? "[{$error['property']}] " : '').$error['message'];
            }

            // Build an informative error message.
            throw new RuntimeException(sprintf('Failed to parse Theme JSON at [%s]. %s.',
                $file,
                implode('. ', $errors)
            ));
        }
    }

    /**
     * Get all of the current attributes for the theme.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the Theme's attributes.
     *
     * @param  array  $attributes
     * @return void
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Fill the theme with an array of attributes.
     *
     * @param  array  $attributes
     * @return void
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Set a given attribute on the Theme.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get an attribute from the theme.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function getAttribute($key, $default = null)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return value($default);
    }

    /**
     * Dynamically retrieve attributes on the object.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the object.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if an attribute exists on the object.
     *
     * @param  string  $key
     * @return void
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Unset an attribute on the object.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }
}
