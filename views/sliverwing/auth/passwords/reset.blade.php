@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">重置密码</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url(config('smsregistration.passwordresetsubmiturl')) }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label for="phone" class="col-md-4 control-label">手机号</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>

                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div id="VerificationCodeDiv" class="form-group{{ $errors->has('VerificationCode') ? ' has-error' : '' }}">
                                <label for="VerificationCode" class="col-md-4 control-label">验证码</label>
                                <div class="col-md-6">
                                    <input id="VerificationCode" type="text" class="form-control" name="VerificationCode" required>
                                    <button id="sendVerificationCodeBtn" type="button" class="btn btn-default" onclick="sendVerificationCode();return false;">获取验证码</button>

                                    <div id="VerificationCodeHelpBlock">
                                        @if ($errors->has('VerificationCode'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('VerificationCode') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <script>
                                        function sendVerificationCode(){
                                            var phone = $('#phone').val();
                                            if (!phone){
                                                addErrorMsg('请输入正确的手机号!');
                                            } else {
                                                $.post('/register/VerificationCode', {'_token': $('input[name="_token"]').val(), 'phone': phone}, function(data){
                                                    if (data['status'] == 'success'){
                                                        $('#VerificationCodeDiv').removeClass("has-error");
                                                        $('#VerificationCodeHelpBlock').empty();
                                                        var timeToWait = <?php echo config('smsregistration.expiration', 60)?>;
                                                        $('#sendVerificationCodeBtn').attr("disabled", true).html(timeToWait + "秒后重试");
                                                        var timeCountDown = setInterval(function(){
                                                            if (timeToWait > 1) {
                                                                $('#sendVerificationCodeBtn').html(--timeToWait + "秒后重试");
                                                            } else {
                                                                clearInterval(timeCountDown);
                                                                $('#sendVerificationCodeBtn').attr("disabled", false).html("发送验证码");
                                                            }
                                                        }, 1000);
                                                    } else {
                                                        addErrorMsg(data.message)
                                                    }
                                                }, 'JSON')
                                            }
                                        }
                                        function addErrorMsg(msg){
                                            $('#VerificationCodeDiv').addClass("has-error");
                                            $('#VerificationCodeHelpBlock').empty().append(
                                                '<span class="help-block">'+
                                                '<strong>' + msg + '</strong>' +
                                                '</span>'
                                            );
                                        }
                                    </script>
                                    @if ($errors->has('VerificationCode'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('VerificationCode') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">密码</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label">确认密码</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        重置密码
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
