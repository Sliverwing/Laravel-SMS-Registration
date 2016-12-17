<?php

namespace Sliverwing\Registration\Traits;

use Illuminate\Http\Request;
use Sliverwing\Alidayu\Jobs\AlidayuMessageJob;

trait SendVerificationCode{

    private $ValidateMessage;

    public function sendVerificationCode(Request $request){
        if (!$this->doValidate($request)){
            return ['status'=>'error', 'message'=>$this->ValidateMessage];
        } else {
            $targetNumber = $request->input('phone');
            $verificationCode = $this->generateVerificationCode();
            $request->session()->put('VerificationCode', $verificationCode);
            $request->session()->put('VerificationPhoneNumber', $targetNumber);
            $request->session()->put('VerificationCodeSendTime', time());
            $this->dispatch(new AlidayuMessageJob($targetNumber, [config('smsregistration.param') => $verificationCode], config('smsregistration.alidayuconfigname')));
            return ['status' => 'success'];
        }
    }

    private function generateVerificationCode(){
        $verificationCode = rand(100000, 999999);
        return $verificationCode;
    }

    private function doValidate(Request $request){
        // TODO Add PhoneNumber Validator
        if (!$request->input('phone', null)){
            $this->ValidateMessage = '请输入手机号';
            return false;
        }
        $lastSentTime = $request->session()->get('VerificationCodeSendTime');
        if ($lastSentTime){
            if (time() - $lastSentTime < config('smsregistration.expiration')){
                $this->ValidateMessage = '您申请验证码过于频繁';
                return false;
            }
        }
        return true;
    }

}
