<?php

use Illuminate\Routing\Controller;

class BaseController extends Controller {

    /**
     * @access   public
     * @return \BaseController
     */
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

}
