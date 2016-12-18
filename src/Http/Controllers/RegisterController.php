<?php

namespace Sliverwing\Registration\Http\Controllers;

use \App\Http\Controllers\Controller;
use Sliverwing\Registration\Traits\SendVerificationCode;
use Sliverwing\Alidayu\Jobs\AlidayuMessageJob;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use SendVerificationCode;

    protected function getJobInstance($targetNumber, $verificationCode){
        return new AlidayuMessageJob($targetNumber, [config('smsregistration.param') => $verificationCode], config('smsregistration.alidayuconfigname'));
    }

}
