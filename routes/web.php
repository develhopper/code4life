<?php

use Core\Route;

Route::get("/","IndexController@index");
Route::get("cache/invalidate","IndexController@invalidateCache");
Route::get("p/{slug}","IndexController@post");
Route::get("login","HomeController@login");
Route::post("login","HomeController@login");
Route::post("register","HomeController@register");