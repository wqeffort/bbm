@extends('lib.home.header')
@section('body')
<style type="text/css">
body {
    background: #EEE;
}
.extension dl {
    display: flex;
    justify-content: left;
    padding: 1rem;
    background: #FFF;
    position: relative;
}
.extension dl dd h3 {
    font-size: 1.3rem;
}
.extension dl dd p,h3 {
    line-height: 1.5rem;
}
.extension dl dd img {
    width: 4.5rem;
    border-radius: 50%;
    margin-right: 2rem;
}
.extension span {
    position: absolute;
    right: 2rem;
    top: 2rem;
    font-size: 2rem;
}
.extension_pic {
    background: #FFF;
    padding: .5rem;
}
.extension_pic ul {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}
.extension_pic ul li img {
    width: 2rem;
    border-radius: 50%;
    padding: .2rem;
}
.text {
    padding: 1rem;
    background: #FFF;
}
.text dd {
    line-height: 2rem;
    color: #666;
}
</style>
<div class="extension">
    <dl>
        <dd>
            <img src="{{ asset($user->user_pic) }}">
        </dd>
        <dd>
            <h3>{{ $user->user_nickname }}</h3>
            <p>我的积分: <b>{{ $user->user_point }}</b></p>
            <p>我的粉丝: <b>{{ $second->count() }}</b></p>
        </dd>
    </dl>
    <span onclick="makeQrcode()"><i class="fa fa-qrcode"></i></span>
</div>
<div class="blank"></div>
{{-- 列出下级用户 --}}
<div class="extension_pic">
    <ul>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>
        <li><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJiccXox0MhiaMFrib7mOzxe19K8smBMDApae26RSzkpF9T4a2XYRQ62dIw6LDibL0sl4jgKYaVaVY3icQ/132"></li>

    </ul>
</div>
<div class="blank"></div>
<div class="text">
    <dl>
        <dt>推广规则说明:</dt>
        <dd>1.安全区群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群群</dd>
        <dd>1.安全区群群群群群群群群群群群群群群群</dd>
        <dd>1.安全区群群群群群群群群群群群群群群群</dd>
        <dd>1.安全区群群群群群群群群群群群群群群群</dd>
    </dl>
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

<script type="text/javascript">
    // 生成个人信息名片
    function makeQrcode() {
        layer.open({
            type: 1
            ,content: '<div style="text-align: center;padding: 1rem;"><dl style="display: flex;justify-content: space-between;"><dd><img style="width:8rem;" src="{{ url('images/logo_new.png') }}" alt="logo" /></dd><dd><h4 style="font-size: 1.2rem;line-height: 3rem;">{{ $user->user_nickname }}</h4><span>会员名片</span></dd></dl><img style="margin:2rem 0;width: 80%;" src="{{ $qrcode }}" alt="qrcode" /><p><a href="javascript:;" style="font-size: 1rem;">点击分享为URL链接</a></p><ol style="font-size: 1rem;">请使用微信扫描上方二维码</ol></div>'
            ,anim: 'up'
            ,style: 'position:fixed; top:15%; left:10%; width: 80%; height: 70%; padding:10px 0; border:none;border-radius:.5rem;'
        });
    }
    wx.ready(function () {
        // 分享
        wx.onMenuShareAppMessage({
            title: 'JOYOUS ASPIRATION ClUB', // 分享标题
            desc: '我是分享的描述', // 分享描述
            link: '{{ $url }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{ asset('images/logo_new.png') }}', // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户点击了分享后执行的回调函数
                layer.closeAll();
                layer.open({
                    content: '分享成功'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            },
            cancel: function () {
                // 用户点击了分享后执行的回调函数
                layer.closeAll();
                layer.open({
                    content: '分享失败'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
        wx.onMenuShareTimeline({
            title: 'JOYOUS ASPIRATION ClUB', // 分享标题
            link: '{{ $url }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{ asset('images/logo_new.png') }}', // 分享图标
            success: function () {
                // 用户点击了分享后执行的回调函数
                layer.closeAll();
                layer.open({
                    content: '分享成功'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            },
            cancel: function () {
                // 用户点击了分享后执行的回调函数
                layer.closeAll();
                layer.open({
                    content: '分享失败'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
    });
</script>

@endsection






































