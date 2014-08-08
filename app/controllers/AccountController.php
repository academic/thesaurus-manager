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
	return View::make('account/index')->with('user', Sentry::getUser());
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
            'email' => 'Required|Email|Unique:users,email,' . Sentry::getUser()->email . ',email',
        );

        if (Input::get('password')) {
            $rules['password'] = 'Required|Confirmed';
            $rules['password_confirmation'] = 'Required';
        }

        $inputs = Input::all();
        $validator = Validator::make($inputs, $rules);

        if ($validator->passes()) {
            $user = User::find(Sentry::getUser()->id);
            $user->first_name = Input::get('first_name');
            $user->last_name = Input::get('last_name');
            $user->email = Input::get('email');
            if (Input::get('password') !== '') {
                $user->password = Hash::make(Input::get('password'));
            }
            $user->save();
            return Redirect::to('account/secure')->with('success', 'Account updated with success!');
        }

        return Redirect::to('account/secure')->withInput($inputs)->withErrors($validator->getMessageBag());
    }

    /**
     * @access   public
     * @return   View
     */
    public function getLogin() {
        if (Sentry::check()) {
            return Redirect::to('account/secure');
        }
        return View::make('account/login');
    }

    /**
     * @access   public
     * @return   Redirect
     */
    public function postLogin() { 
        $errorMessage = FALSE;
        try
        {
            $credentials = array(
                'email'    =>  Input::get('email'),
                'password' => Input::get('password'),
            );
            $user = Sentry::authenticate($credentials, false);
        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            $errorMessage[]='Login field is required.';
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            $errorMessage[]='Password field is required.';
        } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
            $errorMessage[]='Wrong password, try again.';
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            $errorMessage[]= 'User was not found.';
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            $errorMessage[]= 'User is not activated.';
        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            $errorMessage[]= 'User is suspended.';
        } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
            $errorMessage[]= 'User is banned.';
        }
        if($errorMessage){
            return Redirect::to('account/login')->withError($errorMessage);
        }
        return Redirect::to('');
    }

    /**
     * @access   public
     * @return   View
     */
    public function getRegister() {
        if (Sentry::check()) {
            return Redirect::to('account/secure');
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
        Sentry::logout();

        return Redirect::to('account/login')->with('success', 'Logged out with success!');
    }

}
