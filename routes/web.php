<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(
        [
            "status" => "200",
            "body" => [
                "Author" => "Maicol Arryave Alvarez",
                "message" => "Bienvenido a la API de la prueba técnica de Novaheda"
            ]
        ],
        200
    );
});