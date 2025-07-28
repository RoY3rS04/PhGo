<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Royers\Http\ServeMux;
use Royers\Http\Method;
use Royers\Http\{Request, ResponseWriter, StatusCode};

$mux = new ServeMux();

$mux->handleFunc(
    "/",
    Method::Get,
    function (ResponseWriter $w, Request $r) {

        $w->header()->add('Tuani', 'Chatel');
        $w->header()->add('Tuani', 'Perro');
        //var_dump($w->header()->get('Tuani'));

        $w->writeHeader(StatusCode::Ok);

        echo "<pre>";
        var_dump($r->header->entries());
        echo "</pre>";
    }
);

$mux->handleFunc(
    "/snippet/view",
    Method::Get,
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
