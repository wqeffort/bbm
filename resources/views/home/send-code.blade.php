@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #333;
        color: #FFF;
    }
    input {
        text-align: center;
    }
</style>
<div style="text-align: center;padding: 2rem 0;">
    <img src="{{ asset('images/logo.png') }}" style="width: 10rem;">
    {{-- <h1>欢迎加入 Ja Club</h1>
    <span style="color: #CCC;">Welcome to Joyous Aspiration Club</span> --}}
    <h3 style="margin-top: 3rem;">请绑定您的手机号码</h3>
    <ul style="display: flex;justify-content: center;padding: 1rem 0;">
        <li>
            <ul>
                <li style="height: 2rem;margin-top: 1rem;line-height: 2rem;"><span>手 机 号 码:</span></li>
                <li style="height: 2rem;margin-top: 1rem;line-height: 2rem;"><span>短信验证码:</span></li>
            </ul>
        </li>
        <li>
            <ul style="margin-left: .5rem;">
                <li style="height: 2rem;margin-top: 1rem;line-height: 2rem;"><input type="number" name="phone" required="required" style="width: 11rem;"></li>
                <li style="height: 2rem;margin-top: 1rem;line-height: 2rem;display: flex;">
                    <input style="width: 5rem;" type="number" name="code" required="required">
                    <a>
                        <input type="button" id="btn" value="获取验证码" onclick="settime(this)" style="background: #C40000;color: #FFF;border-radius: .5rem;padding: .5rem;height: 2rem;"/>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <a href="javascript:;" onclick="isSmsCode()"><div style="color: #FFF;font-size: 1.5rem;text-decoration: underline;margin-top: 2rem;">点击绑定</div></a>

</div>


<div class="nav-bottom">
    <a href="{{ url('shop') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">服 务</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('article') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">资 讯</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('car') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">购物车</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('user') }}" class="nav-bottom-item false" ng-repeat="i in pages">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>

@endsection

<script type="text/javascript">
    var countdown = 60;
    function settime(val) {
        if (countdown == 60) {
            var phone = $("input[name=phone]").val();
            if (isPhone(phone)) {
                load();
                $.post('{{ url('sendsmsPost') }}', {
                    "_token" : '{{ csrf_token() }}',
                    "phone" : phone
                }, function (res) {
                    var object = $.parseJSON(res);
                    layer.closeAll();
                    if (object.success == 'success') {
                        $('input[name=phone]').attr('disabled','true');
                        layer.open({
                            content: object.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }else{
                        layer.open({
                            content: object.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                })
            }else{
                layer.open({
                    content: '您输入的手机号码不正确,请重新输入!'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                setTimeout(function () {
                        location.reload();
                    },1500);
            }
        }
        if (countdown == 0) {
            val.removeAttribute("disabled");
            val.value="获取验证码";
            countdown = 60;
        } else {
            val.setAttribute("disabled", true);
            val.value="(" + countdown + ")后重发";
            countdown--;
            setTimeout(function() {
                settime(val)
            },1000)
        }
    }

    // 提交短信验证码
    function isSmsCode(phone) {
        load();
        var smsCode = $("input[name=code]").val();
        var phone = $("input[name=phone]").val();
        if (smsCode.length == 4) {
            // 发送ajax进行验证
            $.post('{{ url('addUserInfoPhone') }}',{
                "_token" : '{{ csrf_token() }}',
                "phone" : phone,
                "code" : smsCode
            },function (ret) {
                var obj = $.parseJSON(ret);
                layer.close(loadBox);
                if (obj.status == 'success') {
                    layer.closeAll();
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    setTimeout(function () {
                        location.href='{{ url('/') }}'
                    },1500);
                }else{
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            });
        }else{
            layer.closeAll();
            layer.open({
                content: '您输入的验证码格式不正确!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    }
</script>




































