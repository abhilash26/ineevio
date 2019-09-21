<?php 

// Using namespaces

use core\server\Request;
use core\route\Route;
use core\route\Dispatch;

$request = new Request();

// Register routes

Route::get("/", 'HomeController@index', 'home');
Route::get("/user/login/{user_id}", 'BaseController@index', 'login');
Route::get("/username/int/{username}", function($username){
    echo "Hello $username";
});
Route::post("/home", 'HomeController@index', 'homepage');

// Route::show();


// Dispatch Routes
$dispatch = new Dispatch(Route::$routes); 
$dispatch->dispatchRequest($request);