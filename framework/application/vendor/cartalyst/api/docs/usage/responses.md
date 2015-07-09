## Generating Responses

Cartalyst's API package allows you to easily create RESTFul API responses. It allows you to register routes with the `{api}/<api version>` syntax to indicate your application's API calls.

Responses from an API route should be returned using the `Cartalyst\Api\Http\Response` class (which extends `Illuminate\Http\Response`). Using this class has a number of advantages over string-based responses:

- It allows you to pass objects through internal requests. String-based responses must cast the object to a string and then transform it back, so you lose the ability to interact with the responses returned on internal requests.
- It allows you utilise special array keys, such as `message` and `errors`, which are utilized when creating internal API Exceptions.

Creating responses can be done in two different ways:

1. Using the `Cartalyst\Api\Http\Response` class
2. Using the `Response::api()` function which acts as a layer on top of the `Cartalyst\Api\Http\Response` class

With every response an optional HTTP status code can be provided. You can read more about all the different HTTP status codes [here](http://en.wikipedia.org/wiki/List_of_HTTP_status_codes).

> **Note:** if you didn't register the `Response` alias, you must use `Api::createResponse()` instead of `Response::api()`.

### Generating A Response {#generating-a-response}

---

Creating API responses only requires you to send an data through the `Response::api()` function. This can be a string or an array. Optionally, you can pass along a HTTP status code and your custom headers.

	$data = array('result' => 'foo');

	$response = Response::api($data, $status, $headers);

The output of this response will result an instance of `Cartalyst\Api\Http\Response`.

> **Note:** When defining keys in the root of the data array make sure not to use the `message` or `error` key. Those keys are reserved by the API package to provide informative messages in your API response.

### Registering Response Routes {#registering-response-routes}

---

When creating response routes for your API calls you have to prepend your routes with the `{api}/<api version>` syntax. The `{api}` part will allow Cartalyst's API package to intercept these routes when you're performing internal requests. The `<api version>` allows you to easily create new versions for your API.

Registering a basic route could look like the following example.

	Route::get('{api}/v1/foo', function()
	{
		return Response::api(array('bar'));
	});

### Grouping Responses {#grouping-responses}

---

Grouping response routes by version number will allow you not to add the `{api}/<api version>` for each route separately.

	Route::group(array('prefix' => '{api}/v1'), function()
	{
		Route::get('foo', 'ApiV1\FooController@fooFunction');
		Route::get('bar', 'ApiV1\BarController@BarFunction');
	});
	
	Route::group(array('prefix' => '{api}/v2'), function()
	{
		Route::get('foo', 'ApiV2\FooController@fooFunction');
		Route::get('bar', 'ApiV2\BarController@BarFunction');
	});

### Creating Error Messages {#creating-error-messages}

---

You can provide error messages by sending a string with the error message through the API response and providing the correct HTTP status code.

	Route::get('user/{id}', function($id)
	{
		$user = User::find($id);

		if (is_null($user))
		{
			return Response::api("User [$id] was not found.", 404);
		}

		return Response::api(compact('user'));
	});

Internal requests will be converted to an Exception which can be caught and interacted with. Read more about catching and handling internal request exceptions [here](/api/usage/requests#catching-exceptions).

### Accessing Request Input {#accessing-request-input}

---

If you registered the `Input` and `Request` facade aliases you can use them to safely retrieve the request's input.

	Route::post('{api}/v1/users', function()
	{
		$input = Input::get();

		$user = new User($input);

		// Return with a "201 Created" response.
		return Response::api(compact('user'), 201);
	});

> **Note:** If you didn't register Cartalyst's aliases for Laravel's `Input` and `Request` facades you must use `API::getCurrentRequest()->input()` instead. This makes sure to only retrieve the input for the current request.

### Returned Responses {#returned-responses}

---

As you can see, for the most part, we are not doing anything different to a simple standard API. We are returning objects from our routes. On external requests, these responses are turned into arrays (and then JSON) before being sent to the browser. Internally, the objects you return are accessible.

The API is smart enough to transform any objects which inherit from `Illuminate\Support\Contracts\ArrayableInterface` into arrays, no matter where they lie in the response array.

You can read more on Laravel 4 responses over [at their documentation](http://four.laravel.com/docs/responses).
