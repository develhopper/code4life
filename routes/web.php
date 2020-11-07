<?php

use Core\Route;

$admin=["auth","admin"];

Route::get("/","IndexController@index");
Route::get("cache/invalidate","IndexController@invalidateCache");
Route::get("p/{slug}","IndexController@post");
Route::get("login","HomeController@login");
Route::get("logout","HomeController@logout");
Route::post("login","HomeController@login");
Route::post("register","HomeController@register");

Route::get("admin","AdminController@admin",$admin);
Route::get("admin/new/post","AdminController@new_post",$admin);
Route::get("admin/recent/posts","AdminController@recent_posts",$admin);
Route::get("admin/recent/comments","AdminController@recent_comments",$admin);
Route::get("admin/user_management","AdminController@user_management",$admin);
Route::get("admin/page_settings","AdminController@page_settings",$admin);
Route::get("admin/category_settings","AdminController@category_settings",$admin);

Route::get("admin/notes","AdminController@notes",$admin);
Route::put("admin/notes","AdminController@notes",$admin);