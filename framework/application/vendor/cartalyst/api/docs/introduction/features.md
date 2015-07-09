## Features

Coming soon.

- Configurable Base URLs for API
- API versioning (with default versions for internal API requests to reduce verbosity)
- Driver-based authentication (with out-of-the-box support for Cartalyst's [Sentry package](https://github.com/cartalyst/sentry)).
- Support for internal API requests, where routes on the API may be interacted with, without resorting to cURL or creating a new HTTP request, which would slow down your application. Internal requests are possible (and dead-simple) at runtime with our API package. [^1]

[^1]: These have been referred to in some frameworks as a "HMVC request", though Laravel 4 is not a "MVC" framework so we don't refer to hierarchical requests as "HMVC"