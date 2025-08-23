# PhGo

A lightweight go-like HTTP library for PHP.

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

This simple example will show you how to access dynamic parameters and 
writing response headers.

Let's suppose we have a project that has this structure:

```
├──public
│   └──index.php
├──src
├──vendor
├──composer.json
├──blogs.json
```

**public/index.php**

```php
<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Royers\Http\Method;
use Royers\Http\{Request, Response, StatusCode};
use Royers\Http\ServeMux;

$mux = new ServeMux();

$mux->handleFunc(
    "/blogs/{id}",
    Method::Get,
    function (Response $w, Request $r) {

        $blogsPath = __DIR__ . "/../blogs.json";

        $blogsJson = file_get_contents($blogsPath);
        $blogs = json_decode($blogsJson, true);

        // PhGo doesn't validate data for you
        $blogId = filter_var(
            $r->dynamicParams["id"],
            FILTER_VALIDATE_INT,
            ['default' => 0]
        );

        if ($blogId <= 0) {

            $w->header()->set("Content-Type", "application/json");
            $w->writeHeader(StatusCode::BadRequest);

            echo json_encode(
                [
                    "msg" => "Blog id can't be lower than 1"
                ]
            );

            return;
        }

        $blog = array_find($blogs, fn (array $blog) => $blog['id'] === $blogId);

        if (!$blog) {

            $w->header()->set("Content-Type", "application/json");
            $w->writeHeader(StatusCode::NotFound);

            echo json_encode(
                [
                    "msg" => "Blog with id = {$blogId} not found"
                ]
            );

            return;
        }
        /*
        Notice that you must write all the headers you want to send
        with the header methods, i.e set, add, etc. before throwing any output.
        These headers will be send when you call the writeHeader method.
        */
        $w->header()->set("Content-Type", "application/json");
        $w->writeHeader(StatusCode::Ok);
        echo json_encode($blog);
    }
);

$mux->listen();
```

**blogs.json**

```json
[
  {
    "id": 1,
    "title": "The Rise of Minimalist Web Design",
    "author": "Jane Doe",
    "date": "2025-01-12",
    "tags": ["design", "web", "minimalism"],
    "content": "Minimalist design continues to dominate the web, focusing on clarity and user experience."
  },
  {
    "id": 2,
    "title": "Understanding Pointers in C",
    "author": "John Smith",
    "date": "2025-01-20",
    "tags": ["programming", "C", "pointers"],
    "content": "Pointers are one of the most powerful features of C. In this article, we break down how they work."
  }
  // More blogs...
]
```