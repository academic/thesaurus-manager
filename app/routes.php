<?php

Route::controller('account', 'AccountController');
Route::controller('nodes', 'NodesController');
Route::controller('moderation', 'ModerationController');

Route::get('/', 'HomeController@showIndex');
