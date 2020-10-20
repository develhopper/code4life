<?php
use Core\Route;

Route::post("api/new/comment/{post_id}","ApiController@add_comment");