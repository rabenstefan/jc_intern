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

    use ResetsPasswords {
        ResetsPasswords::getResetValidationRules as private __parent__getResetValidationRules;
    }

    protected $redirectPath = '/';

    protected $send_link_validation = [
            'email' => 'required|email|max:191', // InnoDB (MySQL's engine) can handle VARCHARs only up to 191 when UNIQUE is selected.
            'g-recaptcha-response' => 'required|recaptcha'
        ];

    protected $reset_password_validation = [
        'email' => 'required|email|max:191', // InnoDB (MySQL's engine) can handle VARCHARs only up to 191 when UNIQUE is selected.
        //'g-recaptcha-response' => 'required|recaptcha',   // A captcha might be good here, but is not really required as the protection through the token should be enough
        'password' => 'required|confirmed|min:8|custom_complexity:3'
    ];


    /**
     * This function was not meant to be overloaded like this, but I dont see any other option right now if we want to use ReCaptcha.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function validateSendResetLinkEmail(\Illuminate\Http\Request $request) {
        /**
         * Overloaded function (notice the hardcoded rule for email :sad_face:):
         *
         protected function validateSendResetLinkEmail(Request $request)
        {
        $this->validate($request, ['email' => 'required|email']);
        }
         */
        $this->validate($request, $this->send_link_validation);
    }

    /**
     * Returns validation rules for a password reset.
     *
     * Will be called in ResetsPasswords::reset()
     *
     * @return array
     */
    protected function getResetValidationRules() {
        /**
         * Overloaded function:
         *
        protected function getResetValidationRules()
        {
        return [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
        ];
        }
         */
        return array_merge($this->__parent__getResetValidationRules(), $this->reset_password_validation);
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
