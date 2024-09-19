<?php

// use Core\Router;
// use App\Controllers\HomeController;


// // Define routes
// Router::get('/', [HomeController::class, 'index']);



use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AdminController;
use App\Middlewares\AuthMiddleware;


Router::get('/', [HomeController::class, 'index'], 'home', [AuthMiddleware::class]);
Router::get('/user/{id}', [HomeController::class, 'ab'], 'ab');


Router::group(['prefix' => '/admin', 'middleware' => [AuthMiddleware::class], 'as' => 'admin.'], function () {    
});