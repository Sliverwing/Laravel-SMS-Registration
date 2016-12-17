<?php

namespace Sliverwing\Registration\Http\Controllers;

use App\User;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Sliverwing\Registration\Traits\SendVerificationCode;


class ResetPasswordController extends Controller
{
    use SendVerificationCode;

    public function showResetForm(){
        return view('sliverwing.auth.passwords.reset');
    }

    public function doPasswordReset(Request $request){
        $this->doValidate($request);
        $user = User::where('phone', $request->input('phone'))->first();
        $user->forceFill([
            'password' => bcrypt($request->input('password')),
            'remember_token' => Str::random(60),
        ])->save();
        $this->guard()->login($user);
        return redirect('/home');
    }

    private function doValidate(Request $request){
        return $this->validate($request, [
            'phone' => 'required|exists:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
