## Concepts

### REST {#rest}

---

Represental State Transfer (or REST for short) is essentially a design principle or "pattern" for web services to interact, utilizing the power of the Hypertext Transfer Protocol (HTTP).

Many developers are familiar with the basic of HTTP (POSTing a form, GETting a page) but many would be unfamiliar with what else you can do using HTTP.

[Nettuts+ has a great article](http://net.tutsplus.com/tutorials/other/a-beginners-introduction-to-http-and-rest/) which will give you an introduction to REST.

Additionally, [Apigee](http://apigee.com) have a great [Book on Web API Design](http://info.apigee.com/Portals/62317/docs/web%20api.pdf) which goes into a lot more detail about REST and how to make it awesome.

REST is a design pattern that to be truely "RESTful" you may end up designing an API which is confusing at best for your consumers. Our API package allows for a simple REST API to be developed but also has the functionality to get into complex API design should you wish to.

### Modularity {#modularity}

---

Many applications, such as Cartalyst's Platform are modular applications, with drop-in extensions. We have allowed our API package to conduct hierarchical, internal requests to RESTful routes and receive their responses. This is good because it decouples the component which requested the data and the component which responds to the data, allowing you to override routes. This is better shown in example:

Let's pretend we have an extension, `Extension A`. It has the following routes:

	// Extension A's routes file
	Route::get('{api}/v1/foo', function()
	{
		return Response::api(array('bar'));
	});


Additionally, we have `Extension B`, which calls that route to get the response:

	$response = API::get('v1/foo');

`Extension B` does not know who is responsible for the route. Presumably, it is `Extension A`. Let's add `Extension C`, who's routes override `Extension A`:

	// Extension C's routes file
	Route::get('{api}/v1/foo', function()
	{
		return Response::api(array('baz'));
	});

Now, `Extension B` may still make the same internal request and we have made `Extension C` respond to it without touching any of `Extension B`'s code. Vòila, `modularity` and `separation`.
