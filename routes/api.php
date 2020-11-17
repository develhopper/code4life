<?php
use Core\Route;

$admin=["auth","admin"];

Route::post("new/comment/{post_id}","ApiController@add_comment");
Route::post("check_username","ApiController@check_username");
Route::get("category","ApiController@category");

Route::post("file/listing","FileController@listing",$admin);
Route::post("file/get_url","FileController@get_url",$admin);

Route::post("file/make_dir","FileController@make_dir",$admin);
Route::post("file/make_file","FileController@make_file",$admin);
Route::post("file/copy","FileController@cp",$admin);
Route::post("file/cut","FileController@mv",$admin);
Route::post("file/remove","FileController@remove",$admin);
Route::post("file/get_content","FileController@get_content",$admin);
Route::post("file/put_content","FileController@put_content",$admin);