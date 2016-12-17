<?php

namespace Sliverwing\Registration\Http\Middleware;

use Closure;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class SMSRegistrationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->session()->get('VerificationCode') !== null && $request->input('VerificationCode') == $request->session()->get('VerificationCode') && $request->input('phone') == $request->session()->get('VerificationPhoneNumber')) {
            return $next($request);
        } else {
            $bag = new MessageBag();
            $bag->add('VerificationCode', '请检查您的验证码输入');
            return back()->withInput(
                $request->except('password')
            )->with('errors', $request->session()->get('errors', new ViewErrorBag())->put('default', $bag));
        }
    }
}
