<?php

class AccountController extends AuthorizedController {

    /**
     * @access   protected
     * @var      array
     */
    protected $whitelist = array(
        'getLogin',
        'postLogin',
        'getRegister',
        'postRegister'
    );

    /**
     * @access   public
     * @return   View
     */
    public function getIndex() {
        // Show the page.
        //
		return View::make('account/index')->with('user', Auth::user());
    }

    /**
     *
     * @access   public
     * @return   Redirect
     */
    public function postIndex() {
        $rules = array(
            'first_name' => 'Required',
            'last_name' => 'Required',
            'email' => 'Required|Email|Unique:users,email,' . Auth::user()->email . ',email',
        );

        if (Input::get('password')) {
            $rules['password'] = 'Required|Confirmed';
            $rules['password_confirmation'] = 'Required';
        }

        $inputs = Input::all();
        $validator = Validator::make($inputs, $rules);

        if ($validator->passes()) {
            $user = User::find(Auth::user()->id);
            $user->first_name = Input::get('first_name');
            $user->last_name = Input::get('last_name');
            $user->email = Input::get('email');
            if (Input::get('password') !== '') {
                $user->password = Hash::make(Input::get('password'));
            }
            $user->save();
            return Redirect::to('account')->with('success', 'Account updated with success!');
        }

        return Redirect::to('account')->withInput($inputs)->withErrors($validator->getMessageBag());
    }

    /**
     * @access   public
     * @return   View
     */
    public function getLogin() {
        if (Auth::check()) {
            return Redirect::to('account');
        }

        return View::make('account/login');
    }

    /**
     * @access   public
     * @return   Redirect
     */
    public function postLogin() {
        $rules = array(
            'email' => 'Required|Email',
            'password' => 'Required'
        );

        $email = Input::get('email');
        $password = Input::get('password');
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            if (Auth::attempt(array('email' => $email, 'password' => $password))) {
                return Redirect::to('account')->with('success', 'You have logged in successfully');
            } else {
                return Redirect::to('account/login')->with('error', 'Email/password invalid.');
            }
        }

        return Redirect::to('account/login')->withErrors($validator->getMessageBag());
    }

    /**
     * @access   public
     * @return   View
     */
    public function getRegister() {
        if (Auth::check()) {
            return Redirect::to('account');
        }

        return View::make('account/register');
    }

    /**
     * @access   public
     * @return   Redirect
     */
    public function postRegister() {
        $rules = array(
            'first_name' => 'Required',
            'last_name' => 'Required',
            'email' => 'Required|Email|Unique:users',
            'password' => 'Required|Confirmed',
            'password_confirmation' => 'Required'
        );
        $inputs = Input::all();
        $validator = Validator::make($inputs, $rules);
        if ($validator->passes()) {
            $user = new User;
            $user->first_name = Input::get('first_name');
            $user->last_name = Input::get('last_name');
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            return Redirect::to('account/register')->with('success', 'Account created with success!');
        }

        return Redirect::to('account/register')->withInput($inputs)->withErrors($validator->getMessageBag());
    }

    /**
     * @access   public
     * @return   Redirect
     */
    public function getLogout() {
        Auth::logout();

        return Redirect::to('account/login')->with('success', 'Logged out with success!');
    }

}
