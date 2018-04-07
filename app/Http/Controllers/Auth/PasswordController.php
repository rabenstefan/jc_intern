<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $override_validation = [
            'email' => 'required|email|max:191', // InnoDB (MySQL's engine) can handle VARCHARs only up to 191 when UNIQUE is selected.
            'g-recaptcha-response' => 'required|recaptcha'
        ];

    public function validate(\Illuminate\Http\Request $request, array $rules, array $messages = [], array $customAttributes = []) {
        return parent::validate($request, array_merge($rules, $this->override_validation), $messages, $customAttributes);
    }

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
