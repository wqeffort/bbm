<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="UTF-8">
    <title>S.J.A.G Service Admin System</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
	<link rel="stylesheet" href="{{ asset('css/weadmin.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/js/admin.js') }}" charset="utf-8"></script>
	<script type="text/javascript" src="{{ asset('js/common.js') }}" charset="utf-8"></script>
    <style type="text/css">
        .layui-card-header {
            height: 42px;
            line-height: 42px;
            padding: 0 15px;
            border-bottom: 1px solid #009688;
            border-radius: 2px 2px 0 0;
            font-size: 14px;
            background: #009688;
            color: #FFF;
        }
    </style>
    <script type="text/javascript">
    document.onreadystatechange = loadingChange;//当页面加载状态改变的时候执行这个方法.
        function loadingChange() {
            if(document.readyState == "complete"){ //当页面加载状态为完全结束时进入
                $(".loading").hide();//当页面加载完成后将loading页隐藏
            }
        }
    </script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        (function(){
var network = function(){
 var monitor = this;
 /**
  * @param {Funcation} speedInterval
  */
 var speedInterval = null;
 /**
  * @param {Function} networkInterval
  */
 var networkInterval = null;
 /**
  * @param {Function} reNetworkInterval
  */
 var reNetworkInterval = null;
 var time = 5000;
 /**
  * 获取网络连接状态
  */
 var getConnectState = function(){
  return navigator.onLine ? 1 : 0;
 }; 
 /**
  * 网络中断
  */
 var disconnect = function(){
  // TODO ... 
  // console.log("网速中断");
  window.clearInterval(reNetworkInterval);
  reNetworkInterval = null;
  endSpeed();
  endNetwork();
  window.setTimeout(function(){
   reNetworkInterval = window.setInterval(function(){
    if (getConnectState() == 1) {
     window.clearInterval(reNetworkInterval);
     reNetworkInterval = null;
     startSpeed();
     startNetwork();
    } else {
     window.clearInterval(reNetworkInterval);
     reNetworkInterval = null;
     disconnect();
    }
   }, time);
  }, 10 * time);
 };
 /**
  * 网络速度
  */
 var speed = {
   /**
    * 网速过慢
    */
   bad : function(){
    // TODO ... 
    // console.log("网速过慢");
    $('#network').html(' 网速过慢');
    window.setTimeout(function(){
     if(getConnectState() == 1) {
      window.clearInterval(networkInterval);
      networkInterval = null;
      startSpeed();
     } else {
      disconnect();
     }
    }, 2 * time);
   },
   /**
    * 网速中等
    */
   medium : function(){
    // TODO ... 
    $('#network').html(' 网速中等');
    // console.log("网速中等");
   },
   /**
    * 网速极佳
    */
   great : function(){
    // TODO ... 
    $('#network').html(' 网速极佳');
    // console.log("网速极佳");
   }
 };
 /**
  * 开启速度监测
  * @private
  */
 var startSpeed = function(){
  window.clearInterval(speedInterval);
  speedInterval = null;
  if(getConnectState() == 1) {
   speedInterval = window.setInterval(function(){
    var start = new Date().getTime();
    if (getConnectState() == 1) {
     var img = document.getElementById("networkSpeedImage");
     if (!!!img) {
      img = document.createElement("IMG");
      img.id = "networkSpeedImage";
      img.style.display = "none";
      document.body.appendChild(img);
     }
     try {
      img.src = "http://www.baidu.com/img/baidu_jgylogo3.gif?_t=" + new Date().getTime();
      img.onload = function(){
       var end = new Date().getTime();
       var delta = end - start;
       if (delta > 200) {
        speed.bad();
       } else if (delta > 100) {
        speed.medium();
       } else {
        speed.great();
       }
      };
     } catch(e){
      speed.bad();
     }
    } else {
     // TODO 网络断开
     disconnect();
    }
   }, time);
  }else {
   // TODO 网络断开
   disconnect();
  }
 };
 /**
  * 停止速度监测
  * @private
  */
 var endSpeed = function(){
  window.clearInterval(speedInterval);
  speedInterval = null;
 };
 /**
  * 开启网络连接监测
  * @private
  */
 var startNetwork = function(){
  if (getConnectState() == 1) {
   networkInterval = window.setInterval(function(){
    if (getConnectState() == 0) {
     disconnect();
    }
   }, time);
  } else{
   disconnect();
  }
 };
 /**
  * 结束网络连接监测
  * @private 
  */
 var endNetwork = function(){
  window.clearInterval(networkInterval);
  networkInterval = null;
 };
 /**
  * 网络监控开始
  */
 this.start = function(){
  startNetwork();
  startSpeed();
 };
 /**
  * 停止网络监控
  */
 this.stop = function(){
  endSpeed();
  endNetwork();
 };
};
  window.network = new network();
}).call(this);
network.start();
    </script>
  </head>
    <style type="text/css">
        .weadmin-nav .layui-breadcrumb{
            visibility: visible;
        }
    </style>
<body>
@yield('body')
</body>
</html>