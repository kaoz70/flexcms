## Install & Configure in Laravel 4

Follow the steps below to install the API package in Laravel 4.

### 1. Install Sentry 2 {#install-sentry-2}

---

On a default installation with Laravel, we use Sentry 2 as the authentication driver for our API package. You will need to install Sentry 2 yourself, or, use another authentication driver. Pull Requests are welcome and we can add new drivers to the default installation. We'll then provide it as a simple config option for our Laravel users. 

[Installing Sentry 2 in Laravel 4](http://cartalyst.com/manual/sentry/installation/laravel-4)

### 2. Composer {#composer}

---

Open your `composer.json` file and add the following lines:

	{
		"require": {
			"cartalyst/api": "1.0.*"
		},
		"repositories": [
			{
				"type": "composer",
				"url": "http://packages.cartalyst.com"
			}
		],
		"minimum-stability": "dev"
	}

> **Note:** The minimum-stability key is needed so that you can use the API (which isn't marked as stable, yet).

Run a composer update from the command line.

	php composer.phar update

### 3. Service Provider {#service-provider}

---

Add the following to the list of service providers in `app/config/app.php`.

	'Cartalyst\Api\ApiServiceProvider',

> **Note:** If you are using the Sentry authentication driver for the API package (default with Laravel 4), you will need to ensure that the `SentryServiceProvider` is registered **before** your `ApiServiceProvider`. This will ensure Sentry is prepared before the API package uses it.

### 4. Alias {#alias}

---

Add the following to the to the list of class aliases in `app/config/app.php`.

	'API' => 'Cartalyst\Api\Facades\API',

Override the following aliases to allow the typical use of the `Input`, `Request` and `Response` Facades (not required, but recommended). The examples in our documentation will use these Facades for simplicity.

	'Input'    => 'Cartalyst\Api\Facades\Input',
	'Request'  => 'Cartalyst\Api\Facades\Request',
	'Response' => 'Cartalyst\Api\Facades\Response',

### 5. Configuration {#configuration}

---

After installing, you can publish the package's configuration file into you application by running the following command:

	php artisan config:publish cartalyst/api

This will publish the config file to `app/config/packages/cartalyst/api/config.php` where you can modify the package configuration.
