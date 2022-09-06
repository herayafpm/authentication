<?php

namespace Config;

$routes->group('', ['filter' => 'web', 'namespace' => "App\Controllers\Auth"], function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout', ['filter' => 'web:auth', 'namespace' => "App\Controllers"]);
});