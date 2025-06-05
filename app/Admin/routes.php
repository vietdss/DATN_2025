<?php

use App\Admin\Controllers\CategoryController;
use App\Admin\Controllers\ItemController;
use App\Admin\Controllers\TransactionController;
use App\Admin\Controllers\UserController;
use Illuminate\Routing\Router;
use Encore\Admin\Facades\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    // Đặt các resource route vào bên trong group này
    $router->resource('users', UserController::class);
    $router->resource('items', ItemController::class);
$router->resource('categories', CategoryController::class);

});
