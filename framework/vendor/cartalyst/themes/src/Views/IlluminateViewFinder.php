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

namespace Cartalyst\Themes\Views;

use InvalidArgumentException;
use Cartalyst\Themes\ThemeBag;
use Illuminate\View\FileViewFinder;
use Illuminate\Support\NamespacedItemResolver;

class IlluminateViewFinder extends FileViewFinder implements ViewFinderInterface
{
    /**
     * The theme bag instance.
     *
     * @var \Cartalyst\Themes\ThemeBag
     */
    protected $themeBag;

    /**
     * Get the fullly qualified location of the view.
     *
     * @param  string  $name
     * @return string
     * @throws \InvalidArgumentException
     */
    public function find($name)
    {
        $name = str_replace('.', '/', $name);

        // If the theme bag instance has not been set, we will just let
        // the default handler take control of loading the views.
        if (! isset($this->themeBag)) {
            return parent::find($name);
        }

        // Parse the name
        $resolver = new NamespacedItemResolver;
        list($section, $view) = $resolver->parseKey($name);

        try {
            // If we have a package listed, let's just check firstly
            // if it's actually referring to a hard-coded namespace.
            // Namespaces override packages in Themes, as they do in views.
            if (isset($section)) {
                if (isset($this->hints[$section])) {
                    $sectionType = 'namespaces';
                    $paths = $this->themeBag->getCascadedNamespaceViewPaths($section);
                } else {
                    $sectionType = 'packages';
                    $paths = $this->themeBag->getCascadedPackageViewPaths($section);
                }

                $view  = $this->findInPaths($view, $paths);
            } else {
                $paths = $this->themeBag->getCascadedViewPaths();
                $view  = $this->findInPaths($view, $paths);
            }
        }

        // We couldn't find the view using our theming system
        catch (InvalidArgumentException $e) {
            // Let's fallback to the normal view system.
            try {
                return parent::find($name);
            }

            // If we got an error with the normal view system, we'll
            // catch it here and manipulate the text, so that the user
            // knows we've been searching in the theme as well as the
            // normal view structure. Just a bit friendlier, yeah?
            catch (InvalidArgumentException $e) {
                // Grab the relevent themes from the theme bag
                $active   = $this->themeBag->getActive();
                $fallback = $this->themeBag->getFallback();

                // If we had a section, throw an Exception that's more aimed at
                // debugging why the package does not exist.
                if (isset($section)) {
                    $message = sprintf(
                        'Theme [%s] view [%s] could not be found in theme [%s]',
                        $sectionType,
                        $name,
                        $active->getSlug()
                    );
                } else {
                    $message = sprintf(
                        'Theme view [%s] could not be found in theme [%s]',
                        $name,
                        $active->getSlug()
                    );
                }

                $message .= ($active->getParentSlug()) ? ' or any of its parent themes' : '';
                $message .= ($fallback and $fallback != $active) ? " or the fallback theme [{$fallback->getSlug()}]." : '.';
                $message .= ' The standard view finder has also failed to find the view.';

                throw new InvalidArgumentException($message);
            }
        }

        return $view;
    }

    /**
     * Returns all the namespaces registered with the
     * view finder.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->hints;
    }

    /**
     * Sets the theme bag instance on the view finder.
     *
     * @param  \Cartalyst\Themes\ThemeBag  $themeBag
     * @return void
     */
    public function setThemeBag(ThemeBag $themeBag)
    {
        $this->themeBag = $themeBag;
    }
}
