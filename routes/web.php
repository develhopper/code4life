<?php

use Core\Route;

$admin=["auth","admin"];

Route::post("test","IndexController@test");

Route::get("/","IndexController@index");
Route::get("p/{slug}","IndexController@post");
Route::post("c/{post_id}","IndexController@comment");
Route::get("category/{id}","IndexController@category");
Route::get("login","HomeController@login");
Route::get("logout","HomeController@logout");
Route::post("login","HomeController@login");
Route::post("register","HomeController@register");

Route::get("admin","AdminController@admin",$admin);

Route::get("admin/new/post","AdminController@new_post",$admin);
Route::put("admin/new/post","AdminController@submit_post",$admin);
Route::post("admin/new/post/upload","AdminController@upload_image",$admin);
Route::get("admin/edit/post/{id}","AdminController@edit",$admin);
Route::put("admin/edit/post/{id}","AdminController@submit_edit",$admin);

Route::get("admin/recent/posts","AdminController@recent_posts",$admin);
Route::delete("admin/recent/posts","AdminController@delete_post",$admin);

Route::get("admin/recent/comments","AdminController@recent_comments",$admin);
Route::put("admin/recent/comments","AdminController@comments_action",$admin);

Route::get("admin/user_management","AdminController@user_management",$admin);
Route::get("admin/page_settings","AdminController@page_settings",$admin);

Route::get("admin/category_settings","AdminController@category_settings",$admin);
Route::put("admin/category_settings","AdminController@category_settings",$admin);
Route::delete("admin/category_settings","AdminController@category_delete",$admin);
Route::get("admin/category_settings/{id}","AdminController@category_edit",$admin);
Route::put("admin/category_settings/{id}","AdminController@category_edit",$admin);

Route::get("admin/notes","AdminController@notes",$admin);
Route::put("admin/notes","AdminController@notes",$admin);

Route::get("admin/files","AdminController@files",$admin);

// TEST
Route::get("cache/invalidate","IndexController@invalidateCache");
// Route::get("dummy/posts","IndexController@dummy");
