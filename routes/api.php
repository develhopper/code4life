<?php
use Core\Route;

Route::post("new/comment/{post_id}","ApiController@add_comment");
Route::post("check_username","ApiController@check_username");