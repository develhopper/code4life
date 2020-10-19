<?php

use Core\Route;

Route::get("/","IndexController@index");
Route::get("p/{slug}","IndexController@post");