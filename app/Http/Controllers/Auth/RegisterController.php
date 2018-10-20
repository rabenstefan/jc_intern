<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    // This controller is permanently disabled
    //use RegistersUsers;

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function showRegistrationForm()
    {
        abort(404);
    }

    public function register()
    {
        abort(404);
    }

    protected function guard()
    {
        abort(404);
    }

    protected function registered()
    {
        abort(404);
    }

    protected function validator()
    {
        abort(404);
    }

    protected function create()
    {
        abort(404);
    }
}
