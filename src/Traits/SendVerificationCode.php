<?php

namespace Sliverwing\Registration\Traits;

use Illuminate\Http\Request;

trait SendVerificationCode{

    protected $ValidateMessage;

    public function sendVerificationCode(Request $request){
        if (!$this->doValidate($request)){
            return ['status'=>'error', 'message'=>$this->ValidateMessage];
        } else {
            $targetNumber = $request->input('phone');
            $verificationCode = $this->generateVerificationCode();
            $request->session()->put('VerificationCode', $verificationCode);
            $request->session()->put('VerificationPhoneNumber', $targetNumber);
            $request->session()->put('VerificationCodeSendTime', time());
            $job = $this->getJobInstance($targetNumber, $verificationCode);
            $this->dispatch($job);
            return ['status' => 'success'];
        }
    }

    protected function generateVerificationCode(){
        $verificationCode = rand(100000, 999999);
        return $verificationCode;
    }

    protected function doValidate(Request $request){
        // TODO Add PhoneNumber Validator
        if (!$request->input('phone', null)){
            $this->ValidateMessage = '请输入手机号';
            return false;
        }
        $lastSentTime = $request->session()->get('VerificationCodeSendTime');
        if ($lastSentTime && time() - $lastSentTime < config('smsregistration.expiration')){
            $this->ValidateMessage = '您申请验证码过于频繁';
            return false;
        }
        return true;
    }

    protected function getJobInstance($targetNumber, $verificationCode){
//        Use this trait and ovveride this
    }
}
