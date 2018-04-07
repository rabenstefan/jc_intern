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

    protected $reset_send_link_validation = [
            'email' => 'required|email|max:191', // InnoDB (MySQL's engine) can handle VARCHARs only up to 191 when UNIQUE is selected.
            'g-recaptcha-response' => 'required|recaptcha'
        ];

    protected $reset_password_validation = [
        'email' => 'required|email|max:191', // InnoDB (MySQL's engine) can handle VARCHARs only up to 191 when UNIQUE is selected.
        //'g-recaptcha-response' => 'required|recaptcha',
        'password' => 'required|confirmed|min:8|custom_complexity:3'
    ];


    protected function validateSendResetLinkEmail(\Illuminate\Http\Request $request) {
        $this->validate($request, $this->reset_send_link_validation);
    }

    protected function getResetValidationRules() {
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
