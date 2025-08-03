<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Royers\Http\ServeMux;
use Royers\Http\Method;
use Royers\Http\{Request, ResponseWriter, Router, StatusCode};

$mux = new ServeMux();

$mux->handleFunc(
    "/users/{user_id}/posts/{post_id}",
    Method::Get,
    function (ResponseWriter $w, Request $r) {

        $w->header()->add('Tuani', 'Chatel');
        $w->header()->add('Tuani', 'Perro');

        $w->writeHeader(StatusCode::Ok);

        echo "<pre>";
        var_dump($r->dynamicParams);
        echo "</pre>";
    }
);

$mux->handleFunc(
    "/snippet/view",
    Method::Get,
    function (ResponseWriter $w, Request $r) {
        echo "<pre>";
        var_dump($r->header->entries());
        echo "</pre>";
    }
);

$mux->listen();
