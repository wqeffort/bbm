<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/ch-ui.admin.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	<!--[if IE 7]>
	<link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css">
	<![endif]-->
	<script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/area.js') }}"></script>
	<script type="text/javascript" src="{{ asset('layui/layui.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
	<script type="text/javascript" charset="utf-8" src="{{asset('class/ueditor/ueditor.config.js')}}"></script>
    <script type="text/javascript" charset="utf-8" src="{{asset('class/ueditor/ueditor.all.min.js')}}"> </script>
    <script type="text/javascript" charset="utf-8" src="{{asset('class/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
	{{-- 文件上传js --}}
	<script src="{{ asset('class/uploadify/jquery.uploadify.min.js') }}" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('class/uploadify/uploadify.css') }}">
	<link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<style>
	.uploadify{display:inline-block;}
	.uploadify-button{border:none; border-radius:5px; margin-top:8px;}
	table.add_tab tr td span.uploadify-button-text{color: #FFF; margin:0;}
	.edui-default{line-height: 28px;}
    div.edui-combox-body,div.edui-button-body,div.edui-splitbutton-body {overflow: hidden; height:20px;}
	div.edui-box{overflow: hidden; height:22px;}
</style>
</head>
<body>
@yield('body')
</body>
</html>