<?php

class AuthorizedController extends BaseController {

    protected $whitelist = array();

    /**
     *
     * @access   public
     * @return \AuthorizedController
     */
    public function __construct() {
        parent::__construct();
        $this->beforeFilter('auth', array('except' => $this->whitelist));
    }

}
