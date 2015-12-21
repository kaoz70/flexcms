<?php

/**
 * Part of the Sentinel Social package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel Social
 * @version    2.0.4
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Sentinel\Addons\Social\Models;

use Cartalyst\Sentinel\Users\UserInterface;

interface LinkInterface
{
    /**
     * Store a token with the link.
     *
     * @param  mixed  $token
     * @return void
     */
    public function storeToken($token);

    /**
     * Get the user associated with the social link.
     *
     * @return \Cartalyst\Sentinel\Users\UserInterface  $user
     */
    public function getUser();

    /**
     * Set the user associated with the social link.
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
     * @return void
     */
    public function setUser(UserInterface $user);
}
