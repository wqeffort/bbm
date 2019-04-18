@extends('lib.home.header')
@section('body')
<style type="text/css">
body {
        background-image: url(/images/login_bg.jpg);
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
        justify-content: space-evenly;
        padding: 1rem 10%;
        color: #FFF;
        height: 2rem;
        line-height: 2rem;
    }

</style>
<div class="bg">
    <div class="page">
        <div style="text-align: center">
            <img style="margin: 3rem 0 1rem 0;width: 5rem;" src="{{ url('images/safe.png') }}">
        </div>
        <h2 style="text-align: center;color: #FFF;margin: 1rem 0;">设置加盟商密码</h2>
        <div class="text">
            <ul>
                <li><p>新的密码:</p>
                    <input style="text-align: center;" type="password" placeholder="请输入密码" name="password">
                </li>
                <li>
                    <p>确认密码:</p>
                    <input style="text-align: center;" placeholder="请输入密码" type="password" name="password1">
                </li>
            </ul>
        </div>
        <p style="color: #EEE;text-align: center;">密码长度请保持在6-20位之间</p>
        <a href="javascript:;" onclick="edit()">
            <div style="text-align: center;margin-top: 2rem;">
                <span style="padding: .5rem 5rem;background: #FFF;color: #333;">确 认</span>
            </div>
        </a>
    </div>
</div>

<script type="text/javascript">
    function edit() {
        var password = $('input[name=password]').val();
        var password1 = $('input[name=password1]').val();
        if (password == password1) {
            if (password.length < 6) {
                layer.open({
                    content: '密码长度不能低于6位'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }else{
                load();
                $.post('{{ url('sms/send') }}',{
                    "_token": '{{ csrf_token() }}',
                    "phone" : '{{ session('user')->user_phone }}'
                },function (ret) {
                    layer.close(loadBox);
                    var obj = $.parseJSON(ret);
                    if (obj.status == 'success') {
                        layer.open({
                            type: 1
                            ,content: '<div style="text-align:center;padding:0 1rem 1rem 1rem;"><h3>请输入四位手机验证码</h3><p>验证码已经发送至 <b style="color:#C40000;">'+obj.data+'</b> 的手机</p><input style="margin: 1rem 0;text-align:center;" maxlength="4" type="number" placeholder="请输入四位验证码" name="code"/><ol style="margin-top: 1rem;"><span style="    padding: .5rem 3rem;background: #C40000;color: #FFF;" onclick="sub()">提 交</span></ol></div>'
                            ,anim: 'up'
                            ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 200px; padding:10px 0; border:none;'
                          });
                    }else{
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                });
            }
        }else{
            layer.open({
                content: '两次输入的密码不一致'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    }

    function sub() {
        var code = $('input[name=code]').val();
        var password = $('input[name=password]').val();
        if (code.length != 4) {
            layer.open({
                content: '您输入的验证码格式不正确!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            load();
            $.post('{{ url('join/set/setPasswordUpdate') }}',{
                "_token" : '{{ csrf_token() }}',
                "password" : password,
                "code" : code
            },function (ret) {
                layer.closeAll();
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    location.href = '{{ url('join/set') }}'
                }else{
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    location.href = '{{ url('join/set') }}'
                }
            });
        }
    }

</script>
@endsection






































