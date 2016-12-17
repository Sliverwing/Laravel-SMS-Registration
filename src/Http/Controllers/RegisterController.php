<?php

namespace Sliverwing\Registration\Http\Controllers;

use \App\Http\Controllers\Controller;
use Sliverwing\Registration\Traits\SendVerificationCode;


class RegisterController extends Controller
{
    use SendVerificationCode;
}
