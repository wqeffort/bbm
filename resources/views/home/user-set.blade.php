@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #EEE;
    }
    ul li {
        display: flex;
        justify-content: space-between;
        padding: .5rem 1rem;
        background: #FFF;
        margin-top: .5rem;
        line-height: 3rem;
        height: 3rem;
    }
    ul li i {
        margin-left: 1rem;
    }
    /*#mm-0 {
        position: absolute;
    }*/
    a {
        color:#333;
    }
</style>
<div>
    <ul>
        <li onclick="setNickname()">
            <p>用户昵称:</p>
            <span>{{ $user->user_nickname }} <i class="fa fa-angle-right"></i></span>
        </li>
        <li>
            <p>用户头像</p>
            <span><img style="width: 2.5rem;border-radius: 50%;" src="{{ asset($user->user_pic) }}"><i class="fa fa-angle-right"></i></span>
        </li>
        <li onclick="setUid()">
            <p>身份信息</p>
            <span>
                @if ($user->user_name && $user->user_uid)
                    已认证
                @else
                    <b>个人信息未认证</b>
                @endif
                <i class="fa fa-angle-right"></i>
            </span>
        </li>
        <li onclick="bankList()">
            <p>银行卡设置</p>
            <span>
                @if ($bank)
                    <img src="{{ asset($bank->bank_logo) }}">
                @else
                    <b>未绑定银行卡</b>
                @endif
                <i class="fa fa-angle-right"></i>
            </span>
        </li>
        <a href="{{ url('user/set/ads') }}">
            <li>
                <p>地址设置</p>
                <span>
{{--                     @if ($user->user_ads)
                        点击修改
                    @else
                        <b>未设置地址</b>
                    @endif --}}
                    <i class="fa fa-angle-right"></i>
                </span>
            </li>
        </a>
        <a href="javascript:;" onclick="setPassword()">
            <li>
                <p>登录密码</p>
                <span>
                    重置登录密码
                    <i class="fa fa-angle-right"></i>
                </span>
            </li>
        </a>
        <li onclick="setCashPassword()">
            <p>支付密码</p>
            <span>
                @if ($user->cash_password)
                    点击修改
                @else
                请设置支付密码
                @endif
                <i class="fa fa-angle-right"></i>
            </span>
        </li>
        <a href="{{ url('loginOut') }}">
            <li style="text-align: center;display: flow-root;" onclick="loginOut()">
                <p>退出登录</p>
            </li>
        </a>
        
    </ul>
</div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div style="height: 10rem;"></div>
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
<div id="la">
    <div id="gesturepwd" style="display: none"></div>
</div>
<script type="text/javascript">
    function setNickname() {
        layer.open({
            content: '暂不支持修改昵称'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }
    function setPic() {
        layer.open({
            content: '暂不支持修改头像'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }

    function setCashPassword() {
        location.href = '{{ url('user/set/cashPassword') }}';
    }

    function setUid() {
        location.href = '{{ url('user/set/uid') }}';
    }

    function bankList() {
        location.href = '{{ url('bank/list') }}';
    }

    function setPassword() {
        //询问框
        layer.open({
            content: '您确定要刷新一下本页面吗？'
            ,btn: ['确定重置', '稍后再说']
            ,yes: function(index){
                load();
                $.post('{{ url('user/set/password') }}',{
                    "_token" : '{{ csrf_token() }}'
                },function (ret) {
                    var obj = $.parseJSON(ret);
                    layer.close(loadBox);
                    if (obj.status == 'success') {
                        layer.open({
                            type: 1
                            ,content: '<div style="text-align:center;"><h3>请输入手机验证码</h3><ul><li style="line-height: 2rem;height: 2rem;margin: 0px 2rem;">新的密码: <input type="password" name="password" /></li><li style="line-height: 2rem;height: 2rem;margin: 0px 2rem;">确认密码: <input type="password" name="password_again" /></li><li style="line-height: 2rem;height: 2rem;margin: 0px 2rem;">验 证 码: <input type="number" placeholder="四位数验证码" name="code" style="text-align:center;"/></li></ul><p style="text-align:right;margin: 1rem;">验证码已经发送至 <b style="color:#000;">'+obj.data+'</b></p><div style="margin-top: 2rem;"><span style="padding: .5rem 1rem;background: #C40000;color: #FFF;" onclick="sub()">确定提交</span></div></div>'
                            ,anim: 'up'
                            ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 320px; padding:10px 0; border:none;'
                        });
                    }else{
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                })
              layer.close(index);
            }
        });
    }

    function sub() {
        var password = $('input[name=password]').val();
        var password_again = $('input[name=password_again]').val();
        var code = $('input[name=code]').val();
        if (password == "" || password_again == "") {
            layer.open({
                content: '重置的新密码不能为空!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else if (password != password_again) {
            layer.open({
                content: '两次输入的密码不一致!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else if (code == '') {
            layer.open({
                content: '验证码不能为空!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            load();
            $.post('{{ url('user/set/password/update') }}', {
                "_token" : '{{ csrf_token() }}',
                "password" : password,
                "code" : code
            }, function(ret) {
                layer.closeAll();
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
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
    }
</script>
@endsection






































