@extends('lib.home.header')
@section('body')
<style>
    /*控制轮播进度条*/
    .focus {
        display: none;
    }
    /*控制侧边栏titile*/
    .mm-navbar-top {
        display: none;
    }
    body {
        background: url('images/bg.png');
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
<!-- header Start -->
<header class="home-header">
    <ul>
        <li>
            <p class="index_menu"><a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a></p>
        </li>
        <li>
            <img src="{{ url('images/logo-text.png') }}" alt="">
        </li>
        <li>
            <p id="city">{{-- <i class="fa fa-map-marker"></i> 定位中 --}}</p>
        </li>
    </ul>
</header>
    <!-- header End -->
<div class="page">
    <!-- banner Start -->
    <div class="slider">
        <ul>
             @foreach ($ad as $element)
                <li>
                    <a href="{{ url($element->url) }}"><img src="{{ url($element->img) }}" alt="{{ $element->title }}"></a>
                </li>
            @endforeach
            
        </ul>
        <!-- <div class="search_box">
            <div class="search">
                <input type="number" name="search1" placeholder="请输入商品名称或者关键字进行搜索！" onchange="search1()"/><i class="fa fa-search" aria-hidden="true"></i>
                </div>
        </div> -->
        <script type="text/javascript" src="{{ asset('js/yxMobileSlider.js') }}"></script>
        <script type="text/javascript">
            var height = $(window).height();
            var width = $(window).width();
            // 获取屏幕的高度
            $(".slider").yxMobileSlider({width:width,height:height,during:3000})
        </script>
    </div>
    <!-- banner End -->

    <footer class="index_footer">
        <div class="title_layer">
            <ul>
                <li>
                    <img src="{{ url('images/logo.png') }}" alt="">
                </li>
                <li style="padding: 0 1rem;">
                    <h1>欢迎来到<br>梦享家俱乐部</h1>
                    <span>Welcome to Joyous Aspiration Club!</span>
                </li>
            </ul>
            <i class="fa fa-angle-right"></i>
        </div>

        <div class="title_nav">
            <ul>
                <li onclick="pageMenu()" style="background: -webkit-linear-gradient(left top, #c25de4 , #681594);background: -moz-linear-gradient(left top, #c25de4 , #681594);background: linear-gradient(left top, #c25de4 , #681594);">
                    <i class="fa fa-th-large"></i>
                    <p>主 页</p>
                </li>
                <li onclick="articleHref()" style="background: -webkit-linear-gradient(left top, #5780de , #15389a);background: -moz-linear-gradient(left top, #5780de , #15389a);background: linear-gradient(left top, #5780de , #15389a);">
                    <i class="fa fa-feed"></i>
                    <p>资 讯</p>
                </li>
                <li onclick="shopHref()" style="background: -webkit-linear-gradient(left top, #48e0d3 , #056d7d);background: -moz-linear-gradient(left top, #48e0d3 , #056d7d);background: linear-gradient(left top, #48e0d3 , #056d7d);">
                    <i class="fa fa-shopping-cart"></i>
                    <p>商 店</p>
                </li>
                <li onclick="userHref()" style="background: -webkit-linear-gradient(left top, #ffd764 , #d2691f);background: -moz-linear-gradient(left top, #ffd764 , #d2691f);background: linear-gradient(left top, #ffd764 , #d2691f);">
                    <i class="fa fa-user"></i>
                    <p>我 的</p>
                </li>
            </ul>
        </div>
    </footer>
</div>


<script type="text/javascript">
var userInfoBox = '';
var inputCodeBox = '';
var makeCodeBox = '';
var pageMenuBox = '';
var fn = true;
var openCount = 0;
var codeFn = 0;
    wx.ready(function () {
        // 获取用户坐标位置
        wx.getLocation({
            type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                lng = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                lat = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                if (res.errMsg == 'getLocation:ok') {
                    // 提交定位信息获取当前城市
                    $.post('{{ url('getReverseAds') }}',{
                        "_token" : '{{ csrf_token() }}',
                        "lat" : lat,
                        "lng" : lng,
                    },function (ret) {
                        var obj = $.parseJSON(ret);
                        console.log(obj);
                        if (obj.status == 'success') {
                            $("#city").html('<i class="fa fa-map-marker"></i> '+obj.data);
                        }else{
                            // 不执行任何操作,后期添加地区点选
                        }
                    })
                }else{
                    //提示
                    layer.open({
                        content: '请开启手机(GPS)定位'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        });
    });

    // 定义关闭Page的方法
    function isInfo() {
        if (openCount == 0) {
            openCount = 1;
            // 延时执行Ajax
            setTimeout(function () {
                // Ajax 验证用户是否补全信息
                load();
                $.post('{{ url('isUserInfo') }}', {
                    '_token' : '{{ csrf_token() }}',
                    'uuid' : '{{ session('user')->user_uuid }}'
                }, function(ret) {
                    layer.close(loadBox);
                    var obj = $.parseJSON(ret);
                    if (obj.status == 'success') {
                        // 不执行任何操作,后期嵌入开门广告
                    }else{
                        layer.close(loadBox);
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
    }
function goNext() {
    var phone = $('#phone').val();
    if (phone.length == '11' && isPhone(phone) && codeFn == 0) {
        load();
        $.post('{{ url('sms/send') }}',{
            "_token" : '{{ csrf_token() }}',
            "phone" : phone
        },function (ret) {
            layer.close(loadBox);
            var res = $.parseJSON(ret);
            if (res.status == 'success') {
                codeFn = 1;
                var html = '<h3 style="font-size: 1.5rem;font-weight: 100;padding: 2rem 0;">请输入验证码</h3><div style="display: flex;justify-content: center;padding: 1rem;"><input type="number" name="smsCode" class="now" style="font-size: 3rem; width:10rem;text-align: center;" maxlength="4"/></div><p style="    text-align: right;padding: 0 1rem;font-size: .8rem;font-weight: 100;color: #666;">验证码已发送至 <b>'+phone+'</b> 手机</p><ol style="text-align: center;font-size: 1.2rem;margin-top: 70%;" onclick="isSmsCode('+phone+')">下一步</ol>';
                inputCodeBox = layer.open({
                    type: 1
                    ,content: html
                    ,anim: 'scale'
                    ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;background:#EEE;'
                });
                                    // $('.now').keyup(function(){
                                    //     if($(this).index()<4) {
                                    //         $(this).next('input').focus();
                                    //     }
                                    // });
            }else{
                layer.open({
                    content: res.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        })
    }else{
        layer.open({
            content: '请输入正确的手机号码'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }
}


    function closeUserInfoBox() {
        layer.close(userInfoBox);
        openCount = 0;
    }

    // 提交短信验证码
    function isSmsCode(phone) {
        load();
        var smsCode = $("input[name=smsCode]").val();
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
                content: '您输入的验证码格式不正确!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    }
    // 左侧菜单栏按钮
    $(function() {
        $('nav#leftMenuBtn').mmenu({
            extensions  : [ 'effect-slide-menu' ],
            searchfield : false,
            counters    : false,
            navbars     : [
                {
                    position    : 'top'
                }, {
                }
            ]
        });
    });

    // 调取微信扫码
    function scanQrcode() {
        wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                if (result) {
                    load();
                    // 发送AJAX获取登录权限
                    $.get('{{ url('ticket/validate') }}/'+result, {
                        '_token' : '{{ csrf_token() }}',
                    }, function(ret) {
                        var obj = $.parseJSON(ret);
                        layer.close(loadBox);
                        if (obj.status == 'success') {
                            //询问框
                            layer.open({
                                content: obj.msg
                                ,btn: ['现在兑换', '稍后再说']
                                ,yes: function(index){
                                  $.post('{{ url('ticket/validate') }}/'+result, {
                                    '_token' : '{{ csrf_token() }}'
                                  }, function(res) {
                                    var object = $.parseJSON(res);
                                    if (object.success == 'success') {
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
                                  });
                                }
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

    // 处理服务菜单页面
    function pageMenu() {
        var height = $(window).height();
        var width = $(window).width();
        var html = '<div style="background: #1B2634;height:'+height+'px;padding: 2rem;position: relative;">\
            <img style="width: 10rem;margin-bottom: .5rem;" src="{{ url('images/logo_a.png') }}" alt="" />\
            <div>\
                <ul style="display: flex;justify-content: space-between;padding:1rem;border-top: 1px solid #FFF;">\
                    <a href="javascript:;" onclick="notice()">\
                    <li style="width:6rem;padding: 1rem .5rem;color: #FFF;text-align: center;border-radius: .2rem;background: -webkit-linear-gradient(left top, #0abb17, #026718 );background: -moz-linear-gradient(bottom right, #0abb17, #026718);background: linear-gradient(to bottom right, #0abb17, #026718 );">\
                        <i class="fa fa-heartbeat" style="font-size:2.5rem;"></i>\
                        <p style="margin-top: 1rem;">健康管理</p>\
                    </li>\
                    </a>\
                    <a href="javascript:;" onclick="notice()">\
                    <li style="width:6rem;padding: 1rem .5rem;color: #FFF;text-align: center;border-radius: .2rem;background: -webkit-linear-gradient(left top, #ffef33 , #cc6105);background: -moz-linear-gradient(bottom right, #ffef33, #cc6105);background: linear-gradient(to bottom right, #ffef33 , #cc6105);">\
                        <i class="fa fa-home" style="font-size:2.5rem;"></i>\
                        <p style="margin-top: 1rem;">金屋管家</p>\
                    </li>\
                    </a>\
                </ul>\
                <ul style="display: flex;justify-content: space-between;padding:1rem">\
                    <a href="javascript:;" onclick="notice()">\
                    <li style="width:6rem;padding: 1rem .5rem;color: #FFF;text-align: center;border-radius: .2rem;background: -webkit-linear-gradient(left top, #248cf7 , #0358a0);background: -moz-linear-gradient(bottom right, #248cf7, #0358a0);background: linear-gradient(to bottom right, #248cf7 , #0358a0);">\
                        <i class="fa fa-plane" style="font-size:2.5rem;"></i>\
                        <p style="margin-top: 1rem;">出行定制</p>\
                    </li>\
                    </a>\
                    <a href="{{ url('rtsh') }}">\
                        <li style="width:6rem;padding: 1rem .5rem;color: #FFF;text-align: center;border-radius: .2rem;background: -webkit-linear-gradient(left top, red , #a70000);background: -moz-linear-gradient(bottom right, red, #a70000);background: linear-gradient(to bottom right, red , #a70000);">\
                        <i class="fa fa-line-chart" style="font-size:2.5rem;"></i>\
                        <p style="margin-top: 1rem;">理财顾问</p>\
                    </li>\
                    </a>\
                </ul>\
            </div>\
            <div onclick="closePageMenu()" style="color: #FFF;border: 1px solid #FFF;height: 2.5rem;line-height: 2.5rem;text-align: center;border-radius: .2rem;margin: 1rem;">返回首页</div>\
        </div>';
        pageMenuBox = layer.open({
            type: 1
            ,content: html
            ,anim: 'up'
            ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
        });
    }

    function closePageMenu() {
        layer.close(pageMenuBox);
    }

    function userHref() {
        location.href = '{{ url('user') }}';
    }
    function shopHref() {
        location.href = '{{ url('shop') }}';
    }
    function articleHref() {
        location.href = '{{ url('article') }}';
    }
</script>
@if (session('jachat'))
@else
<script>
$(function () {
    isInfo();
});
</script>
@endif

@endsection