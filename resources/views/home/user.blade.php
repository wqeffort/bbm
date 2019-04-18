@extends('lib.home.header')
@section('body')
<style>
    body {
        background: #EEE;
    }
    /*控制轮播进度条*/
    .focus {
        display: none;
    }
    /*控制侧边栏titile*/
    .mm-navbar-top {
        display: none;
    }
    .now {
        width: 3rem;
        height: 3rem;
        border:1px #cccccc solid;
        margin: 0 .5rem;
    }
    .now:focus {
        border:1px #587d18 solid;
    }
</style>

<div class="user_bg">
    {{-- {{dd($user)}} --}}
 	@if ($user->user_pic)
 		<img class="blur" src="{{ url($user->user_pic) }}">
 	@else
 		<img class="blur" src="">
 	@endif
    <div class="user_nav">
        <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
        <a href="">{{-- <i class="fa fa-cog"></i> --}}</a>
    </div>
    <div class="user_info">
    	@if ($user->user_pic)
    		<img src="{{ url($user->user_pic) }}">
    	@else
    		<img src="">
    	@endif
        {{-- onclick="payRank()" --}}
        <h4 >{{ $user->user_nickname }}<span>[
            @switch($user->user_rank)
                @case(0)
                    普通用户
                    @break
                @case(1)
                    体验会员
                    @break
                @case(2)
                    男爵会员
                    @break
                @case(3)
                    子爵会员
                    @break
                @case(4)
                    伯爵会员
                    @break
                @case(5)
                    侯爵会员
                    @break
                @case(6)
                    公爵会员
                    @break
                @case(10)
                    内部员工
                    @break
                @default
                    系统繁忙
            @endswitch
            ]</span></h4>
    </div>
    <div class="user_menu">
        <a href="{{ url('user/point/list') }}">
            <span>{{ $user->user_point + $user->user_point_give + $user->user_point_open }}</span>
            <p>积 分</p>
        </a>
        <a href="{{ url('user/collection') }}">
            <span>{{ $collection }}</span>
            <p>收 藏</p>
        </a>
        <a href="{{ url('user/card') }}">
            <span>{{ $card }}</span>
            <p>卡 券</p>
        </a>
    </div>
</div>

<div class="user_team">
    <h3>我的订单</h3>
    <ul>
        <a href="{{ url('order/send/all') }}">
            <li>
                <img src="{{ asset('images/icon-span.png') }}">
                <p>全部订单</p>
            </li>
        </a>
        <a href="{{ url('order/send/no') }}">
            <li>
                <img src="{{ asset('images/icon-dfh.png') }}">
                <p>待发货</p>
            </li>
        </a>
        <a href="{{ url('order/send/on') }}">
            <li>
                <img src="{{ asset('images/icon-dsh.png') }}">
                <p>待收货</p>
            </li>
        </a>
        <a href="javascript:;" onclick="notice()">
            <li>
                <img src="{{ asset('images/icon-dpj.png') }}">
                <p>待评论</p>
            </li>
        </a>
    </ul>
</div>
<div class="user_team">
    <h3>我的钱包</h3>
    <ul>
        {{-- <li>
            <img src="{{ asset('images/icon-ax.png') }}">
            <p>铵享账户</p>
        </li> --}}
        <a href="{{ url('wallet/bond') }}">
            <li>
                <img src="{{ asset('images/icon-cq.png') }}">
                <p>理财账户</p>
            </li>
        </a>
        {{-- <li>
            <img src="{{ asset('images/icon-gq.png') }}">
            <p>股权账户</p>
        </li>
        <li>
            <img src="{{ asset('images/icon-lc.png') }}">
            <p>理财账户</p>
        </li> --}}
    </ul>
</div>
<div class="user_team">
    <h3>其他服务</h3>
</div>
<div class="user_box">
    <a href="{{ url('user/set') }}">
        <img src="{{ asset('images/icon-info.png') }}">
        <p>个人信息</p>
    </a>
    {{-- <a href="{{ url('extension') }}">
        <img src="{{ asset('images/icon-qrcode.png') }}">
        <p>我的推广</p>
    </a> --}}
    <a href="{{ url('cash') }}">
        <img src="{{ asset('images/icon-recharge.png') }}">
        <p>兑换操作</p>
    </a>
    <a href="{{ url('user/share/qrcode') }}">
        <img src="{{ asset('images/icon-share.png') }}">
        <p>分享我们</p>
    </a>
</div>
{{-- <div class="user_box">
    
    <a href="">
        <img src="{{ asset('images/icon-together.png') }}">
        <p>加入我们</p>
    </a>
    <a href="">
        <img src="{{ asset('images/icon-server.png') }}">
        <p>联系我们</p>
    </a>
</div> --}}
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
<div style="height: 5rem"></div>
<div class="nav-bottom" style="position: fixed;">
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('shop') }}">
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">主 页</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('article') }}">
        <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">资 讯</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('car') }}">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">购物车</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item true" ng-repeat="i in pages" href="{{ url('user') }}">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>
<script type="text/javascript">
var userInfoBox = '';
var inputCodeBox = '';
var makeCodeBox = '';
var pageMenuBox = '';
var fn = true;

$(function () {
    isInfo();
});
    // 定义关闭Page的方法
    function isInfo() {
        // 延时执行Ajax
        setTimeout(function () {
            // Ajax 验证用户是否补全信息
            $.post('{{ url('isUserInfo') }}', {
                '_token' : '{{ csrf_token() }}',
                'uuid' : '{{ session('user')->user_uuid }}'
            }, function(ret) {
                layer.close(loadBox);
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    // 不执行任何操作,后期嵌入开门广告
                }else{
                    // 提示补全信息
                    layer.open({
                            content: '亲爱的梦享家会员,用户检测到您还未补全手机号码!'
                            ,btn: ['现在补全', '稍后再说']
                            ,yes: function(index){
                                location.href = 'http://jaclub.shareshenghuo.com/addUserInfoPhone';
                                layer.close(index);
                            }
                        });
                }
            });
        },1500);

    }

    function closeUserInfoBox() {
        layer.close(userInfoBox);
    }

    // 提交短信验证码
    function isSmsCode(phone) {
        load();
        var smsCode1 = $("input[name=smsCode1]").val();
        var smsCode2 = $("input[name=smsCode2]").val();
        var smsCode3 = $("input[name=smsCode3]").val();
        var smsCode4 = $("input[name=smsCode4]").val();
        var smsCode = smsCode1+smsCode2+smsCode3+smsCode4;
        if (smsCode.length == 4) {
            // 发送ajax进行验证
            $.post('{{ url('isSmsCode') }}',{
                "_token" : '{{ csrf_token() }}',
                "smsCode" : smsCode
            },function (ret) {
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    // 传入后端进行补全资料
                    $.post('{{ url('addUserInfoPhone') }}',{
                        "_token" : '{{ csrf_token() }}',
                        "phone" : phone
                    },function (ret) {
                        var obj = $.parseJSON(ret);
                        layer.close(loadBox);
                        if (obj.status == 'success') {
                            layer.open({
                                content: '请按照格式输入验证码'
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                            layer.closeAll();
                        }else{
                            layer.open({
                                content: obj.msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                        }
                    });
                }else{
                    layer.close(loadBox);
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            });
        }else{
            layer.open({
                content: '请按照格式输入验证码'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    }

    // 调取微信扫码
    function scanQrcode() {
        wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                alert(result);
                if (result) {
                    load();
                    // 发送AJAX获取登录权限
                    $.post('{{ url('admin/login') }}', {
                        '_token' : '{{ csrf_token() }}',
                        'key' : result,
                        'uuid' : '{{ session('user')->user_uuid }}'
                    }, function(ret) {
                        var obj = $.parseJSON(ret);
                        layer.close(loadBox);
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
                }else{
                    layer.open({
                        content: 'Sorry,通讯失败!未获取到二维码信息'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        });
    }


    // 购买会籍
    function payRank() {
        location.href = '{{ url('member') }}';
    }

    wx.ready(function () {
        var shareData = {
            title: '<?php if (session('user')){echo session('user')->user_nickname;}?> 邀请您加入梦想家',
            desc: 'JOYOUS ASPIRATION CLUB',//这里请特别注意是要去除html
            link: 'http://{{ env('HTTP_HOST') }}',
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

@endsection
