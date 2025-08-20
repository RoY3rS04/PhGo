<?php


require_once __DIR__ . "/../vendor/autoload.php";

use Royers\Http\Method;
use Royers\Http\Request;
use Royers\Http\Response;
use Royers\Http\ServeMux;

$mux = new ServeMux();

$mux->handleFunc(
    "/users/{id}",
    Method::Get,
    function (Response $w, Request $r) {
        echo "<pre>";
        var_dump($r);
        echo "</pre>";
    }
);

$mux->listen();
