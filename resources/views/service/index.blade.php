@extends('lib.service.header')
@section('body')
	<style type="text/css">
	.layui-layer-demo .layui-layer-title {
	    border: none;
	    background-color: #009488;
	    color: #fff;
	}
	#note {
		display: block;
	    width: 300px;
	    height: 150px;
	    min-width: 300px;
	    min-height: 152px;
	    line-height: 20px;
	    padding: 10px 20px;
	    border: none;
	    box-sizing: border-box;
	    color: #666;
	    word-wrap: break-word;
	}
	</style>
		<!-- 顶部开始 -->
		<div class="container">
			<div class="logo">
				<a href="{{ url('service/') }}"><img style="width: 1.8rem;height: 1.8rem;margin-bottom: .3rem;" src="{{ url('img/logo_m.png') }}"> <span style="padding: 0 1rem;">S.J.A.Group</span></a>
			</div>

			{{-- <ul class="layui-nav left fast-add" lay-filter="">
				<li class="layui-nav-item">
					<a href="javascript:;">+新增</a>
					<dl class="layui-nav-child">
						<!-- 二级菜单 -->
						<dd>
							<a onclick="WeAdminShow('资讯','https://www.youfa365.com/')"><i class="iconfont">&#xe6a2;</i>资讯</a>
						</dd>
						<dd>
							<a onclick="WeAdminShow('图片','http://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a>
						</dd>
						<dd>
							<a onclick="WeAdminShow('用户','https://www.youfa365.com/')"><i class="iconfont">&#xe6b8;</i>用户</a>
						</dd>
					</dl>
				</li>
			</ul> --}}
			<ul class="layui-nav layui-layout-left left left-open"  lay-filter="">>
		        <li class="layui-nav-item layadmin-flexible" lay-unselect="">
		            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
		              	<div class="left_open">
							<i title="展开左侧栏" class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
						</div>
		            </a>
		        </li>
		        <li class="layui-nav-item" lay-unselect="">
		            <a href="javascript:;" onclick="reload()" layadmin-event="refresh" title="刷新">
		              	<i class="layui-icon layui-icon-refresh-3"></i>
		            </a>
		        </li>
		        <li class="layui-nav-item">
					<a href="javascript:;" onclick="note()" layadmin-event="note" title="便签">
				        <i class="layui-icon layui-icon-note"></i>
				    </a>
				</li>
		        <li class="layui-nav-item layui-hide-xs" lay-unselect="">
		            <a href="javascript:;" onclick="network()" title="前台">
		              	<i class="layui-icon layui-icon-website" id="network"></i>
		            </a>
		       	</li>
		        <span class="layui-nav-bar" style="left: 38px; top: 48px; width: 0px; opacity: 0;"></span>
		    </ul>
			<ul class="layui-nav right" lay-filter="">
				<li class="layui-nav-item">
					<img style="width: 1.5rem;border-radius: 50%;" src="{{ url($admin->user_pic) }}">
				</li>
				<li class="layui-nav-item">
					<a href="javascript:;">{{ $admin->user_name }}</a>
					<dl class="layui-nav-child">
						<!-- 二级菜单 -->
						<dd>
							<a onclick="WeAdminShow('个人信息','http://www.baidu.com')">个人信息</a>
						</dd>
						<dd>
							<a href="javascript:;">修改密码</a>
						</dd>
						<dd>
							<a class="loginout" href="javascript:;">退出</a>
						</dd>
					</dl>
				</li>
				<li class="layui-nav-item to-index">
					<a href="/">查看前台</a>
				</li>
			</ul>

		</div>
		<!-- 顶部结束 -->
		<!-- 中部开始 -->
		<!-- 左侧菜单开始 -->
		<div class="left-nav">
			<div id="side-nav">
				<ul id="nav">
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe726;</i>
							<cite>管理员管理</cite>
							<i class="iconfont nav_right">&#xe70c;</i>
						</a>
						<ul class="sub-menu sub-li">
							<li>
								<a _href="{{ url('service/admin') }}">
									<i class="iconfont">&#xe6a7;</i>
									<cite>管理员列表</cite>
								</a>
							</li>
							{{-- <li>
								<a _href="./pages/admin/role.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>角色管理</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/admin/cate.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>权限分类</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/admin/rule.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>权限管理</cite>
								</a>
							</li> --}}
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe6e5;</i>
							<cite>会员管理</cite>
							<i class="iconfont nav_right">&#xe70c;</i>
						</a>
						<ul class="sub-menu sub-li">
							<li>
								<a _href="{{ url('service/user') }}">
									<i class="iconfont">&#xe770;</i>
									<cite>会员列表</cite>

								</a>
							</li>
							<li>
								<a _href="{{ url('service/join') }}">
									<i class="iconfont">&#xe621;</i>
									<cite>加盟商列表</cite>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe705;</i>
							<cite>文章管理</cite>
							<i class="iconfont nav_right">&#xe70c;</i>
						</a>
						<ul class="sub-menu sub-li">
							<li>
								<a _href="./pages/article/list.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>文章列表</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/article/category.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>分类管理</cite>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe723;</i>
							<cite>订单管理</cite>
							<i class="iconfont nav_right">&#xe70c;</i>
						</a>
						<ul class="sub-menu sub-li">
							<li>
								<a _href="./pages/order/list.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>订单列表</cite>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe6ce;</i>
							<cite>APP设置项</cite>
							<i class="iconfont nav_right">&#xe70c;</i>
						</a>
						<ul class="sub-menu sub-li">
							<li>
								<a _href="{{ url('service/app/info') }}">
									<i class="iconfont">&#xe6a7;</i>
									<cite>版本迭代</cite>
								</a>
							</li>
							<li>
								<a _href="{{ url('service/app/banner') }}">
									<i class="iconfont">&#xe6a7;</i>
									<cite>轮播图设置</cite>
								</a>
							</li>
							<li>
								<a _href="{{ url('service/app/ad') }}">
									<i class="iconfont">&#xe6a7;</i>
									<cite>广告图设置</cite>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<i class="iconfont">&#xe70b;</i>
									<cite>推送管理</cite>
									<i class="iconfont nav_right">&#xe70c;</i>
								</a>
								<ul class="sub-menu">
									<li>
										<a _href="">
											<i class="iconfont">&#xe6a7;</i>
											<cite>推送列表</cite>
										</a>
									</li>
									<li>
										<a _href="">
											<i class="iconfont">&#xe6a7;</i>
											<cite>单点推送</cite>
										</a>
									</li>
									<li>
										<a _href="">
											<i class="iconfont">&#xe6a7;</i>
											<cite>推送设置</cite>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<!-- <div class="x-slide_left"></div> -->
		<!-- 左侧菜单结束 -->
		<!-- 右侧主体开始 -->
		<div class="page-content">
			<div class="layui-tab tab" lay-filter="wenav_tab" id="WeTabTip" lay-allowclose="true">
				<ul class="layui-tab-title" id="tabName">
					<li>我的桌面</li>
				</ul>
				<div class="layui-tab-content">
					<div class="layui-tab-item layui-show">
						<iframe src='{{ url('service/info') }}' frameborder="0" scrolling="yes" class="weIframe"></iframe>
					</div>
				</div>
			</div>
		</div>
		<div class="page-content-bg"></div>
		<!-- 右侧主体结束 -->
		<!-- 中部结束 -->
		<!-- 底部开始 -->
		<div class="footer">
			<div class="copyright">Copyright ©2019 S.J.A.G By Leo</div>
		</div>
		<!-- 底部结束 -->
		<script type="text/javascript">
			// 点击改变div颜色
			$(function(){
    			$(".sub-li li").bind("click",function(){
	        		$(this).siblings('li').removeClass('left-nav-onclick');  // 删除其他兄弟元素的样式
	        		$(this).addClass('left-nav-onclick');
    			});
    		});
		</script>

	<!--Tab菜单右键弹出菜单-->
	<ul class="rightMenu" id="rightMenu">
        <li data-type="fresh">刷新</li>
        <li data-type="current">关闭当前</li>
        <li data-type="other">关闭其它</li>
        <li data-type="all">关闭所有</li>
    </ul>
<script type="text/javascript">
	function network() {
		layer.msg('实时网络监测');
	}

	// 刷新页面
	function reload() {
		layer.msg('刷新页面中....');
		setTimeout(function () {
			location.reload();
		}, 1000);
	}

	function note() {
		layer.open({
		  	type: 1,
		  	skin: 'layui-layer-demo', //样式类名
		  	closeBtn: 1, //不显示关闭按钮
		  	anim: 1,
		  	title:'便签',
		  	offset: 'rt',
		  	shade: false,
		  	// shadeClose: false, //开启遮罩关闭
		  	content: '<div><textarea id="note" cols="30" rows="10">'+$.cookie('note')+'</textarea></div>'
		});

		$('#note').bind('input propertychange', function() {
		    $.cookie('note', $(this).val(), { expires: 365, path: '/' });
		});
	}


</script>
@endsection