<?php

Route::filter('auth', function() {
    if (!Sentry::check()) {
        return Redirect::to('/account/login');
    }
});

Route::filter('admin', function() {
    $user = Sentry::getUser();
    if (!$user || ($user && !$user->hasAccess('admin'))) {
        return App::abort(401, 'Not authenticated');
    }
});
Route::when('admin/*', 'admin');
Route::when('secure/*', 'auth');

Route::group(array('prefix' => 'account'), function() {
    Route::get('secure', array('uses' => 'AccountController@getIndex'));
    Route::post('secure', array('uses' => 'AccountController@postIndex'));
    Route::get('login', array('uses' => 'AccountController@getLogin'));
    Route::post('login', array('uses' => 'AccountController@postLogin'));
    Route::get('register', array('uses' => 'AccountController@getRegister'));
    Route::post('register', array('uses' => 'AccountController@postRegister'));
    Route::get('logout', array('uses' => 'AccountController@getLogout'));
});


Route::group(array('prefix' => 'nodes'), function() {
    Route::get('', array('uses' => 'NodesController@getIndex'));
    Route::get('search', array('uses' => 'NodesController@getSearch'));
    Route::post('search', array('uses' => 'NodesController@postSearch'));
    Route::get('secure/add', array('uses' => 'NodesController@getAdd'));
    Route::get('secure/addnode/{relatedId}', array('uses' => 'NodesController@getAddnode'));
    Route::get('secure/add', array('uses' => 'NodesController@getAdd'));
    Route::post('secure/add', array('uses' => 'NodesController@postAdd'));
    Route::post('secure/add-synonym', array('uses' => 'NodesController@postAddSynonym'));
    Route::get('graph/{id}', array('uses' => 'NodesController@getGraph'));
});

Route::controller('admin/moderation', 'ModerationController');


Route::get('/', 'HomeController@showIndex');
