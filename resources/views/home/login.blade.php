@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background-image: url(images/login_bg.jpg);
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }
    .bg {
        position: absolute;
        left: 0px;
        right: 0;
        top: 0px;
        bottom: 0px;
        background: rgba(0,0,0,0.5);
    }
    .logo {
        text-align: center;
    }
    ul li {
        display: flex;
        justify-content: space-between;
        padding: 1rem 20%;
        color: #FFF;
        height: 2rem;
        line-height: 2rem;
    }
</style>

<div class="bg">
    <div class="page">
        <div class="logo">
            <img style="width: 15rem;margin: 3rem 0 2rem 0;" src="{{ url('images/logo.png') }}">
        </div>
        <div class="text">
            <ul>
                <li><p>账 号:</p>
                    <input style="border: 0;border-radius: 0;background: none;border-bottom: 1px solid #FFF;text-align: center;    color: #FFF;" type="number" placeholder="请输入绑定的电话号码" name="phone">
                </li>
                <li>
                    <p>密 码:</p>
                    <input style="border: 0;border-radius: 0;background: none;border-bottom: 1px solid #FFF;text-align: center;    color: #FFF;" placeholder="请输入密码" type="password" name="password">
                </li>
            </ul>
        </div>
        <a href="javascript:;" onclick="sign()">
            <div style="text-align: center;margin-top: 4rem;">
                <span style="padding: .5rem 5rem;background: #FFF;color: #333;">登 录</span>
            </div>
        </a>
        <a href="{{ url('/') }}">
            <div style="text-align: center;margin-top: 2rem;color:#FFF;">
                <img style="width: 3rem;background: #FFF;border-radius: 50%;" src="{{ url('images/wechat.png') }}">
                <p style="padding: .5rem;">微信授权登录</p>
            </div>
        </a>
    </div>
</div>

<script type="text/javascript">
    // 密码登录
    function sign() {
        var phone = $('input[name=phone]').val();
        var password = $('input[name=password]').val();
        if (isPhone(phone)) {
            $.post('{{ url('login/password') }}', {
                "_token" : '{{ csrf_token() }}',
                "phone" : phone,
                "password" : password
            }, function(ret) {
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    location.href = '{{ url('/') }}'
                }else{
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            });
        }else{
            layer.open({
                content: '您输入的电话号码格式不正确!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    }
</script>
@endsection






































