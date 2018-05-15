# laravel-xml-middleware
A Laravel Middleware to accept XML requests

## Configuration
### Install Through Composer
```
composer require tucker-eric/laravel-xml-middleware
```

### Register The Service Provider
In `config/app.php` add the service provider to the providers array:

```php
    'providers' => [
        //Other Service Providers
        XmlMiddleware\XmlRequestServiceProvider::class,
    ];
```

### Register the middleware
In `app/Http/Kernel.php`

```php
    protected $routeMiddleware = [
            /// Other Middleware
            'xml' => \XmlMiddleware\XmlRequestMiddleware::class,
        ];
```

### Applying the middleware to routes
Add the middleware to your route as desired

#### Controller Middleware
```php
class MyController extends Controller
{
    public function __construct()
    {
        $this->middleware('xml');
    }
}
```

#### Route Middleware
```php
    Route::group(['middleware' => 'xml'], function() {
        Route::post('my-api-endpoint', 'MyOtherController@store');
    });
```
```php
        Route::post('my-api-endpoint', 'MyOtherController@store')->middleware('xml');
```
### Accessing XML Input With Middleware
If you are using the middleware it will automatically inject the xml into the request as an array and you you can access the xml data in your controller with the `$request->all()`:

```php

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyController extends Controller
{
    public function __construct()
    {
        $this->middleware('xml');
    }
    
    public function store(Request $request)
    {
        $request->all();
    }
}
```
### Accessing XML Input
To access the xml input without the middleware use the `xml()` method on the `Request`:

```php
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

Class MyOtherController extends Controller
{
    public function store(Request $request)
    {
        $xml = $request->xml();
    }
}
```

To access the xml request as an object pass `false` to the `xml()` method:

```php
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

Class MyOtherController extends Controller
{
    public function store(Request $request)
    {
        $xml = $request->xml(false);
    }
}
```
