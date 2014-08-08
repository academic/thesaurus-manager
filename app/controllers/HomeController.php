<?php

class HomeController extends BaseController {

    public function showIndex() {
        if (!Sentry::check()) {
            return View::make('anonym');
        } else {
            return View::make('dashboard', array('admin' => Sentry::getUser()->hasAccess('admin')));
        }
    }

}
