<?php

namespace Config;

$routes->group('', ['filter' => 'web', 'namespace' => "App\Controllers\Auth"], function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout', ['filter' => 'web:auth', 'namespace' => "App\Controllers\Auth"]);
});
$login_redir = config('Auth')->redirect_login;
$routes->group($login_redir, ['filter' => 'web:auth', 'namespace' => "App\Controllers"."\\".ucwords($login_redir)], function ($routes) {
    $login_redir = config('Auth')->redirect_login;
    $routes->get('', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('profile', 'DashboardController::profile');
    $routes->post('profile', 'DashboardController::updateProfile');
    $routes->group('master', ['filter' => 'web:auth', 'namespace' => "App\Controllers"."\\".ucwords($login_redir)."\Master"], function ($routes) {
        $routes->group('admin', function ($routes) {
            $routes->get('', 'AdminController::index');
            $routes->post('datatable', 'AdminController::datatable');
            $routes->group('add', function ($routes) {
                $routes->get('', 'AdminController::add');
                $routes->post('', 'AdminController::add');
            });
            $routes->group('(:segment)', function ($routes) {
                $routes->get('detail', 'AdminController::detail/$1');
                $routes->post('delete', 'AdminController::delete/$1');
                $routes->post('restore', 'AdminController::restore/$1');
                $routes->group('edit', function ($routes) {
                    $routes->get('', 'AdminController::edit/$1');
                    $routes->post('', 'AdminController::edit/$1');
                });
            });
        });
        
    });
});