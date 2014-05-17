<?php 

Route::controller('account','AccountController' );
Route::controller('nodes','NodesController' );

Route::get('/', 'HomeController@showIndex');
