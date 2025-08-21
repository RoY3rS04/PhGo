# PhGo

A ligthweight go-like HTTP library for PHP.

## Installation

Since the library is still being developed you must install it this way 

```bash
composer require royers/phgo:dev-main 
```

## Usage

In order to be able to use this package you'll need to use these components: 
- A ServeMux object.
- Your own functions which will be injected a Response and Request object by default.

### Example on how to register a route

**index.php**

```php
<?php 

require_once __DIR__ . "/../vendor/autoload.php";

use Royers\Http\ServeMux;
use Royers\Http\Method;
use Royers\Http\{Response, Request};

$mux = new ServeMux();

$mux->handleFunc(
    "/users/{user_id}/blogs/{blog_id}",
    Method::Get,
    function (Response $w, Request $r) {
        /*
            Here lies your code, you have access
            to all methods in Response and Request objects.
        */
    }
);

$mux->listen();