<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Royers\Http\ServeMux;
use Royers\Http\Methods;
use Royers\Http\Header;

echo "<pre>";
$header = new Header();
var_dump($header->headers);
echo "</pre>";

echo "<pre>";
var_dump(getallheaders());
echo "</pre>";

echo "<br>";

echo "<pre>";
var_dump($_SERVER);
echo "</pre>";
die();

$mux = new ServeMux();

$mux->handleFunc(
    "/",
    Methods::Get,
    function () {
        echo "Welcome Home!";
        echo "<br>";
        echo "<a href='/snippet/view'>See Snippet</a>";
    }
);

$mux->handleFunc(
    "/snippet/view",
    Methods::Get,
    function () {
        echo "<pre>";
        var_dump(
            [
              "snippets" => [
                [
                  "id" => 1,
                  "content" => "tuani_perro"
                ],
                [
                  "id" => 2,
                  "content" => "dogseano"
                ]
              ]
            ]
        );
        echo "</pre>";
    }
);

$mux->listen();
