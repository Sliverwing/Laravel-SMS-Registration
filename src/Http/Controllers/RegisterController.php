<?php

namespace Sliverwing\Registration\Http\Controllers;
use Sliverwing\Alidayu\Jobs\AlidayuMessageJob;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private $request;
    private $ValidateMessage;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sendVerificationCode(){
        if (!$this->doValidate()){
            return ['status'=>'error', 'message'=>$this->ValidateMessage];
        } else {
            $targetNumber = $this->request->input('phone');
            $verificationCode = $this->generateVerificationCode();
            $this->request->session()->put('VerificationCode', $verificationCode);
            $this->request->session()->put('VerificationCodeSendTime', time());
            $this->dispatch(new AlidayuMessageJob($targetNumber, [config('smsregistration.param') => $verificationCode], config('smsregistration.alidayuconfigname')));
            return ['status' => 'success'];
        }
    }

    private function generateVerificationCode(){
        $verificationCode = rand(000000, 999999);
        return $verificationCode;
    }

    private function doValidate(){
        // TODO Add PhoneNumber Validator
        if (!$this->request->input('phone', null)){
            $this->ValidateMessage = '请输入手机号';
            return false;
        }
        $lastSentTime = $this->request->session()->get('VerificationCodeSendTime');
        if ($lastSentTime){
            if (time() - $lastSentTime < config('smsregistration.expiration')){
                $this->ValidateMessage = '您申请验证码过于频繁';
                return false;
            }
        }
        return true;
    }

}
