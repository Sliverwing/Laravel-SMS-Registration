<?php

namespace Sliverwing\Registration\Traits;

use Illuminate\Foundation\Auth\AuthenticatesUsers as OriginalAuthenticatesUsers;

trait AuthenticatesUsers {

    use OriginalAuthenticatesUsers;

    public function username(){
        return config('smsregistration.username');
    }

}