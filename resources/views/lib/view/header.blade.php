<!DOCTYPE html>
<html lang="zh-cn">
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
    <script type="text/javascript" src="{{ asset('layer/mobile/layer.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <!--[if IE 7]>
    <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css">
    <![endif]-->
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
@yield('body')
</body>
<style type="text/css">
    img {
        height: 100%;
    }
    .nav-bottom {
        height: 3rem;
        background: #FFF;
        border-top: 1px solid #6193f0;
    }
    .nav-bottom-text {
        line-height: 2rem;
        font-size: 1.2rem;
    }
    .nav-bottom-item {
        height: 0;
        color: #999;
    }
    .nav-bottom-item.true {
        /* opacity: 1; */
        /* background: #FFF; */
        /* color: #000; */
        border-bottom: 2px solid #6193f0;
        height: 1.5rem;
        /*padding-left: 0;*/
        /*padding-right: 0;*/
        color: #333;
    }
</style>
</html>
