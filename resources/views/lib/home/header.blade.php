<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ja Club</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- 指示IE以目前可用的最高模式显示内容 -->
        <meta http-equiv="X-UA-Compatible" content="IE=Emulate IE7"><!-- 指示IE使用 <!DOCTYPE> 指令确定如何呈现内容。标准模式指令以IE7 标准模式显示，而 Quirks 模式指令以 IE5 模式显示。 -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-title" content="Ja Club"><!-- 添加到主屏后的标题（iOS 6 新增） -->
        <meta name="apple-mobile-web-app-capable" content="yes"><!-- 是否启用 WebApp 全屏模式 -->
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"><!-- 设置状态栏的背景颜色  -->
        <!-- 只有在 "apple-mobile-web-app-capable" content="yes" 时生效
        content 参数：
        default 默认值。
        black 状态栏背景是黑色。
        black-translucent 状态栏背景是黑色半透明。
        设置为 default 或 black ,网页内容从状态栏底部开始。
        设置为 black-translucent ,网页内容充满整个屏幕，顶部会被状态栏遮挡。 -->

        <!-- apple-touch-icon 图片自动处理成圆角和高光等效果。
        apple-touch-icon-precomposed 禁止系统自动添加效果，直接显示设计原图。 -->
        <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-57x57-precomposed.png"><!-- iPhone 和 iTouch，默认 57x57 像素，必须有   -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed.png"><!-- iPad，72x72 像素，可以没有，但推荐有  -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed.png"><!-- Retina iPhone 和 Retina iTouch，114x114 像素，可以没有，但推荐有 -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-144x144-precomposed.png"><!-- Retina iPad，144x144 像素，可以没有，推荐大家使用   -->
        <meta name="apple-mobile-web-app-title" content="标题"><!-- title最好限制在六个中文长度内，超长的内容会被隐藏，添加到主屏后的标题（iOS 6 新增） -->
        <link rel="apple-touch-startup-image" sizes="768x1004" href="/splash-screen-768x1004.png"><!-- iPad 竖屏 1536x2008（Retina）   -->
        <link rel="apple-touch-startup-image" sizes="1536x2008" href="/splash-screen-1536x2008.png"><!-- iPad 横屏 1024x748（标准分辨率）   -->
        <link rel="apple-touch-startup-image" sizes="1024x748" href="/Default-Portrait-1024x748.png"><!-- iPad 横屏 2048x1496（Retina）   -->
        <link rel="apple-touch-startup-image" sizes="2048x1496" href="/splash-screen-2048x1496.png">
      <!--   iPhone 和 iPod touch 的启动画面是包含状态栏区域的。
        iPhone/iPod Touch 竖屏 320x480 (标准分辨率)  -->
        <link rel="apple-touch-startup-image" href="/splash-screen-320x480.png"><!-- iPhone/iPod Touch 竖屏 640x960 (Retina)   -->
        <link rel="apple-touch-startup-image" sizes="640x960" href="/splash-screen-640x960.png"><!-- iPhone 5/iPod Touch 5 竖屏 640x1136 (Retina)   -->
        <link rel="apple-touch-startup-image" sizes="640x1136" href="/splash-screen-640x1136.png">
        <link rel="apple-touch-startup-image" href="Startup.png">  <!-- 当用户点击主屏图标打开 WebApp 时，系统会展示启动画面，在未设置情况下系统会默认显示该网站的首页截图，当然这个体验不是很好 -->
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <!--[if IE 7]>
    <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css">
    <![endif]-->
    <script type="text/javascript" src="{{ asset('layer/mobile/layer.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('layui/layui.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <!-- <link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}" /> -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.mmenu.all.css') }}">
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.mmenu.min.all.js') }}"></script>
    @if (session('app'))
        <script type="text/javascript" charset="utf-8">
            wx.config( {!! session('app')->jssdk->buildConfig(array('updateAppMessageShareData','updateTimelineShareData','onMenuShareTimeline','onMenuShareAppMessage','getLocation', 'openLocation', 'scanQRCode'), false) !!} );
            // , 'updateAppMessageShareData', 'updateTimelineShareData'
            wx.ready(function () {
            var shareData = {
                title: '<?php if (session('user')){echo session('user')->user_nickname;}?> 邀请您加入梦享家',
                desc: 'JOYOUS ASPIRATION CLUB',//这里请特别注意是要去除html
                link: 'http://{{ env('HTTP_HOST') }}/handlePid?user_pid=<?php if (session('user')) {echo session('user')->user_uuid;}?>&join_pid=<?php if (session('user')) {echo session('user')->join_pid;}?>',
                imgUrl: '<?php if (session('user')){echo session('user')->user_pic;}else{echo env('HTTP_HOST')."/images/logo.png";}?>'
            };
            if(wx.onMenuShareAppMessage){ //微信文档中提到这两个接口即将弃用，故判断
                wx.onMenuShareAppMessage(shareData);//1.0 分享到朋友
                wx.onMenuShareTimeline(shareData);//1.0分享到朋友圈
            }else{
                wx.updateAppMessageShareData(shareData);//1.4 分享到朋友
                wx.updateTimelineShareData(shareData);//1.4分享到朋友圈
            }
        });
        </script>
    @endif
@yield('body')
<script type="text/javascript">
phoneBoxSub = '';
codeBoxSub = '';
    // 左侧菜单栏按钮
    $(function() {
        $('nav#leftMenuBtn').mmenu({
            extensions  : [ 'effect-slide-menu' ],
            searchfield : false,
            counters    : false,
            navbars     : [
                {
                    position    : 'top'
                }
            ]
        });
    });

    function isJoinLogin() {
        load();
        // 检查是否已经登录
        // 发送ajax检查是否存在登录
        $.post('{{ url('joinIsLogin') }}',{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.close(loadBox);
                location.href = "{{ url('join') }}"
            }else{
                $(isJoinPhone());
            }
        });
    }

    function isJoinPhone() {
        // 弹出登录层
        phoneBoxSub = layer.open({
            type: 1
            ,content: '<div style="background:url(/images/join_bg.jpg) no-repeat left top; background-size:cover;position: absolute;width: 100%;height: 100%;text-align:center;">\
                <span class="layer_close_btn" onclick="closeLogin()">X</span>\
                <img style="width: 12rem;padding: 3rem 0 1rem 0;" src="/images/join_logo.png" alt="lgo" />\
                <ul>\
                    <li style="display: flex;justify-content: center;height: 2rem;line-height: 2rem;">\
                        <p style="padding: 0 1rem 0 0;">账 号:</p>\
                        <input type="number" style="text-align:center;" name="joinPhone" value="" />\
                    </li><br>\
                    <li style="display: flex;justify-content: center;height: 2rem;line-height: 2rem;">\
                        <p style="padding:0 1rem 0 0;">密 码:</p>\
                        <input type="password" style="text-align:center;" name="joinPassword" /></li>\
                </ul>\
                <ol style="position: absolute;top: 60%;width: 100%;text-align: center;height: 3rem;">\
                    <span style="background: #188aff;position: relative;color: #FFF;border-radius: 1rem;padding:.5rem 3rem;" onclick="subJoin()">立即登录</span>\
                </ol>\
                </div>'
            ,anim: 'up'
            ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
        });
        layer.close(codeBoxSub);
    }
    // <p>使用 <b style="text-decoration: underline;" onclick="isJoinCode()">短信验证码登录</b></p>\
    function isJoinCode() {
        codeBoxSub = layer.open({
            type: 1
            ,content: '<div style="background:url(/images/join_bg.jpg) no-repeat left top; background-size:cover;position: absolute;width: 100%;height: 100%;text-align:center;"><span class="layer_close_btn" onclick="closeLogin()">X</span><img style="width: 12rem;padding: 3rem 0 1rem 0;" src="/images/join_logo.png" alt="lgo" /><ul><li style="    display: flex;justify-content: center;height: 2rem;line-height: 2rem;"><p style="padding: 0 1rem 0 0;">账 号:</p><input type="number" style="text-align:center;" name="joinCodePhone" value="" /></li></ul><ol style="margin: 2rem 0 1rem 0;"><span style="background: #188aff;color: #FFF;border-radius: 1rem;padding:.5rem 3rem;" onclick="joinGetCode()">获取验证码</span></ol><p>使用 <b style="text-decoration: underline;" onclick="isJoinPhone()">账号密码登录</b></p></div>'
            ,anim: 'up'
            ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
        });
        layer.close(phoneBoxSub);
    }
    function closeLogin() {
        layer.closeAll();
    }
    function joinGetCode() {
        var phone = $('input[name=joinCodePhone]').val();
        if (isPhone(phone)) {
            load();
            $.post('{{ url('sms/send') }}',{
                "_token" : '{{ csrf_token() }}',
                "phone" : phone
            },function (ret) {
                layer.close(loadBox);
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    layer.open({
                        type: 1
                        ,content: '<div style="text-align:center;padding:0 1rem 1rem 1rem;"><h3>请输入四位手机验证码</h3><p>验证码已经发送至 <b style="color:#C40000;">'+obj.data+'</b> 的手机</p><input style="margin: 1rem 0;text-align:center;" maxlength="4" type="number" placeholder="请输入四位验证码" name="joinCode"/><ol style="margin-top: 1rem;"><span style="    padding: .5rem 3rem;background: #C40000;color: #FFF;" onclick="subJoinCode('+obj.data+')">提 交</span></ol></div>'
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
        }else{
            layer.open({
                content: '您输入的手机号码格式不正确'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    }

    function subJoinCode(phone) {
        var code = $('input[name=joinCode]').val();
        if (code.length == 4) {
            load();
            $.post('{{ url('isJoin') }}',{
                "_token" : '{{ csrf_token() }}',
                "code" : code,
                "phone" : phone
            },function (ret) {
                layer.close(loadBox);
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    layer.closeAll();
                    location.href = "{{ url('join') }}"
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

    // 账号密码登录加盟商
    function subJoin() {
        var phone = $('input[name=joinPhone]').val();
        var password = $('input[name=joinPassword]').val();
        if (isPhone(phone)) {
            if (password.length > 5) {
                load();
                $.post('{{ url('isJoin') }}',{
                    "_token" : '{{ csrf_token() }}',
                    "password" : password,
                    "phone" : phone
                },function (ret) {
                    layer.close(loadBox);
                    var obj = $.parseJSON(ret);
                    if (obj.status == 'success') {
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        layer.closeAll();
                        location.href = "{{ url('join') }}"
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
                    content: '密码长度不能低于6位'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        }else{
            layer.open({
                content: '您输入的手机号码格式不正确'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    }


</script>
<!-- leftNav Start -->
<nav id="leftMenuBtn">
    <ul>
        <li>
            <a href="{{ url('/') }}">
                <p>
                    <i class="fa fa-th"></i>
                    <span style="margin-left: 1rem;">首 页</span>
                </p>
            </a>
        </li>
        <li>
            <a href="http://mdc.jaclub.cn">
                <p>
                    <i class="fa fa-heartbeat"></i>
                    <span style="margin-left: 1rem;">健康管理</span>
                </p>
            </a>
        </li>
        <li>
            <a href="javascript:;" onclick="notice()">
                <p>
                    <i class="fa fa-home"></i>
                    <span style="margin-left: 1rem;">金屋管家</span>
                </p>
            </a>
        </li>
        <li>
            <a href="javascript:;" onclick="notice()">
                <p>
                    <i class="fa fa-plane"></i>
                    <span style="margin-left: 1rem;">出行定制</span>
                </p>
            </a>
        </li>
        <li>
            <a href="{{ url('rtsh') }}">
                <p>
                    <i class="fa fa-line-chart"></i>
                    <span style="margin-left: 1rem;">理财顾问</span>
                </p>
            </a>
        </li>
        <li style="padding-right: 2rem;line-height: 1rem;border:0;text-align: center;color:#333;">
            <dl style="display: flex;justify-content: space-between;padding-top: 1rem;">
                <a href="{{ url('member') }}">
                    <dd>
                        <img style="width: 4rem;" src="{{ asset('images/member.png') }}">
                        <p style="color:#333;padding: 1rem 0;">成为会员</p>
                    </dd>
                </a>
                <a href="{{ url('join/login') }}">{{-- onclick="isJoinLogin()" --}}
                    <dd>
                        <img style="width: 4rem;" src="{{ asset('images/agent.png') }}">
                        <p style="color:#333;padding: 1rem 0;">加 盟 商</p>
                    </dd>
                </a>
                <a href="{{ url('recharge') }}">
                    <dd>
                        <img style="width: 4rem;" src="{{ asset('images/recharge.png') }}">
                        <p style="color:#333;padding: 1rem 0;">二次充值</p>
                    </dd>
                </a>
            </dl>
            <dl style="display: flex;justify-content: space-between;padding-top: 1rem;">
                
                <a href="javascript:;" onclick="notice()">
                    <dd>
                        <img style="width: 4rem;" src="{{ asset('images/server.png') }}">
                        <p style="color:#333;padding: 1rem 0;">联系客服</p>
                    </dd>
                </a>
            </dl>
        </li>
    </ul>
    
</nav>
<!-- leftNav End -->
</body>
</html>
