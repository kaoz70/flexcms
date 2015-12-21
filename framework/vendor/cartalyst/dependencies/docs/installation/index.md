## Installation

> **Note:** To use Cartalyst's Dependencies package you need to have a valid Cartalyst.com subscription.
Click [here](https://www.cartalyst.com/pricing) to obtain your subscription.

### 1. Composer {#composer}

----

Open your `composer.json` file and add the following lines

	{
		"repositories": [
			{
				"type": "composer",
				"url": "http://packages.cartalyst.com"
			}
		],
		"require": {
			"cartalyst/dependencies": "1.0.*",
		}
	}

Run composer update from the command line

	composer update
