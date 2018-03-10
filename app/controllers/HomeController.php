<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function __construct()
    {
        // $this->beforeFilter('guest', ['except' => ['getLogout', 'showWelcome']]);
    }

    public function showWelcome()
	{
		return Redirect::to('/dashboard');
	}

	public function getLogin()
    {
        return View::make('home.login');
    }

    public function postLogin()
    {
        $credentials = [
            'email'     => Input::get('email'),
            'password'  => Input::get('password'),
        ];

        $remember_me = Input::has('remember_me');

        if ( Auth::viaRemember() || Auth::attempt($credentials, $remember_me) ) {
            return Redirect::to('/');
        }

        return Redirect::back()->with('error-message', trans('auth.login_failed'));
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('login')->with('success-message', trans('auth.logout_success'));
    }

}
