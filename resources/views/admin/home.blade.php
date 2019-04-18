@extends('lib.admin.header')
@section('body')
{{-- {{ dd(session('admin')) }} --}}
	<!--头部 开始-->
	<div class="top_box">
		<div class="top_left">
			<div class="logo">
				Ja Club
			</div>
			<ul>
				<li><a href="#">首页</a></li>
				<li><a href="{{ url('admin/user/list') }}" target="main">搜索</a></li>
			</ul>
		</div>
		<div class="top_right">
			<ul>
                {{-- <li><a href="{{ url('admin/list') }}" target="main">权限管理</a></li> --}}
				<li>
                    {{-- @switch(session('admin')->rank)
                        @case(1)
                            普通权限
                            @break
                        @case(2)
                            高级权限
                            @break
                        @case(3)
                            财务权限
                            @break
                        @case(9)
                            超级权限
                            @break
                        @default
                            未知用户
                    @endswitch --}}
                    管理员：<img style="padding: 0 1rem;" class="user_pic" src="{{ $admin->user_pic }}" alt="">{{ $admin->user_nickname }}</li>
                {{-- <li>
                    所属部门:<td class="tc">
                    @switch(session('admin')->cate)
                        @case(0)
                            产品研发
                            @break
                        @case(1)
                            运营部门
                            @break
                        @case(2)
                            凤天呈祥
                            @break
                        @case(3)
                            梦味意求
                            @break
                        @case(4)
                            金屋良缘
                            @break
                        @case(5)
                            愿走高飞
                            @break
                        @case(6)
                            融通四海
                            @break
                    @endswitch
                    </td>
                </li> --}}
				<li><a href="javascript:;" onclick="loginOut()">登出</a></li>
			</ul>
		</div>
	</div>
	<!--头部 结束-->

	<!--左侧导航 开始-->
	<div class="menu_box">
		<ul>
			<li>
            	<h3><i class="fa fa-fw fa-cog"></i>站点设置</h3>
                <ul class="sub_menu">
                    <li><a href="{{ url('admin/list') }}" target="main"><i class="fa fa-fw fa-cubes"></i>权限管理</a></li>
                    <li><a href="{{ url('admin/ad/list') }}" target="main"><i class="fa fa-fw fa-cubes"></i>广告列表</a></li>
                    {{-- <li><a href="{{ url('admin/ad/add') }}" target="main"><i class="fa fa-fw fa-cubes"></i>添加广告</a></li> --}}
                    <li><a href="{{ url('admin/article/list') }}" target="main"><i class="fa fa-fw fa-cubes"></i>文章列表</a></li>
                    <li><a href="{{ url('admin/article/add') }}" target="main"><i class="fa fa-fw fa-database"></i>添加文章</a></li>
                </ul>
            </li>
            <li>
                <h3><i class="fa fa-fw fa-cog"></i>用户管理</h3>
                <ul class="sub_menu">
                    <li><a href="{{ url('admin/user/info') }}" target="main"><i class="fa fa-fw fa-plus-square"></i>信息统计</a></li>
                    <li><a href="{{ url('admin/user/list') }}" target="main"><i class="fa fa-fw fa-cubes"></i>用户列表</a></li>
                    {{-- <li><a href="{{ url('admin/user/recharge') }}" target="main"><i class="fa fa-fw fa-database"></i>用户充值</a></li> --}}
                </ul>
            </li>
            @if (session('admin')->cate == 3 || session('admin')->cate == 0)
                <li>
                    <h3><i class="fa fa-fw fa-cog"></i>商城管理</h3>
                    <ul class="sub_menu">
                        <li><a href="{{ url('admin/order/info') }}" target="main"><i class="fa fa-fw fa-plus-square"></i>信息统计</a></li>
                        <li><a href="{{ url('admin/goods/list') }}" target="main"><i class="fa fa-fw fa-plus-square"></i>商品列表</a></li>
                        <li><a href="{{ url('admin/brand/list') }}" target="main"><i class="fa fa-fw fa-list-ul"></i>商品品牌</a></li>
                        <li><a href="{{ url('admin/category/list') }}" target="main"><i class="fa fa-fw fa-list-alt"></i>商品分类</a></li>
                        <li><a href="{{ url('admin/attr/list') }}" target="main"><i class="fa fa-fw fa-image"></i>商品属性</a></li>
                        <li><a href="{{ url('admin/order/no') }}" target="main"><i class="fa fa-fw fa-image"></i>未发货订单</a></li>
                        <li><a href="{{ url('admin/order/on') }}" target="main"><i class="fa fa-fw fa-image"></i>已发货订单</a></li>
                        <li><a href="{{ url('admin/order/help') }}" target="main"><i class="fa fa-fw fa-image"></i>代客下单</a></li>
                    </ul>
                </li>
                <li>
                    <h3><i class="fa fa-fw fa-cog"></i>临时活动</h3>
                    <ul class="sub_menu">
                        <li><a href="{{ url('admin/ticket') }}" target="main"><i class="fa fa-fw fa-cubes"></i>周年售票</a></li>
                    </ul>
                </li>
                <li>
                    <h3><i class="fa fa-fw fa-cog"></i>加盟商管理</h3>
                    <ul class="sub_menu">
                        <li><a href="{{ url('admin/join/order') }}" target="main"><i class="fa fa-fw fa-cubes"></i>审批列表</a></li>
                        <li><a href="{{ url('admin/join/list') }}" target="main"><i class="fa fa-fw fa-cubes"></i>加盟商列表</a></li>
                        <li><a href="{{ url('admin/join/add') }}" target="main"><i class="fa fa-fw fa-database"></i>添加加盟商</a></li>
                        <li><a href="{{ url('admin/join/cash') }}" target="main"><i class="fa fa-fw fa-database"></i>加盟商兑换</a></li>
                        <li><a href="{{ url('admin/join/city') }}" target="main"><i class="fa fa-fw fa-database"></i>城市合伙人</a></li>
                    </ul>
                </li>
            @endif
            @if (session('admin')->cate == 4 || session('admin')->cate == 0)
                <li>
                    <h3><i class="fa fa-fw fa-cog"></i>融通四海</h3>
                    <ul class="sub_menu">
                        <li><a href="{{ url('admin/rtsh/info') }}" target="main"><i class="fa fa-fw fa-cubes"></i>信息统计</a></li>
                        <li><a href="{{ url('admin/rtsh/list') }}" target="main"><i class="fa fa-fw fa-cubes"></i>项目列表</a></li>
                        <li><a href="{{ url('admin/rtsh/order/list') }}" target="main"><i class="fa fa-fw fa-database"></i>订单列表</a></li>
                        <li><a href="{{ url('admin/rtsh/order/refund') }}" target="main"><i class="fa fa-fw fa-database"></i>返息列表</a></li>
                        <li><a href="{{ url('admin/rtsh/order/join/refund') }}" target="main"><i class="fa fa-fw fa-database"></i>加盟商提成</a></li>
                        <li><a href="{{ url('admin/rtsh/cash') }}" target="main"><i class="fa fa-fw fa-database"></i>兑换列表</a></li>
                        <li><a href="{{ url('admin/rtsh/account') }}" target="main"><i class="fa fa-fw fa-database"></i>账户调度</a></li>
                    </ul>
                </li>
            @endif
            @if (session('admin')->cate == 5 || session('admin')->cate == 0)
                <li>
                    <h3><i class="fa fa-fw fa-cog"></i>公账财务</h3>
                    <ul class="sub_menu">
                        <li><a href="{{ url('admin/finance/list') }}" target="main"><i class="fa fa-fw fa-cubes"></i>审批列表</a></li>
                        <li><a href="{{ url('admin/finance/cash') }}" target="main"><i class="fa fa-fw fa-cubes"></i>提现列表</a></li>
                        <li><a href="{{ url('admin/finance/cashAll') }}" target="main"><i class="fa fa-fw fa-cubes"></i>历史提现</a></li>
                    </ul>
                </li>
            @endif
            @if (session('admin')->cate == 6 || session('admin')->cate == 0)
                <li>
                    <h3><i class="fa fa-fw fa-cog"></i>外账财务</h3>
                    <ul class="sub_menu">
                        <li><a href="{{ url('admin/finance/foreign/cash') }}" target="main"><i class="fa fa-fw fa-cubes"></i>提现列表</a></li>
                        <li><a href="{{ url('admin/finance/foreign/cash/all') }}" target="main"><i class="fa fa-fw fa-cubes"></i>历史提现</a></li>
                    </ul>
                </li>
            @endif
                <li>
                    <h3><i class="fa fa-fw fa-cog"></i>报表导出</h3>
                    <ul class="sub_menu">
                        <li><a href="{{ url('admin/table/spring/cash') }}" target="main"><i class="fa fa-fw fa-cubes"></i>春蚕提现报表</a></li>
                        <li><a href="{{ url('admin/table/join/cash') }}" target="main"><i class="fa fa-fw fa-cubes"></i>佣金提现报表</a></li>
                        <li><a href="{{ url('admin/table/finance') }}" target="main"><i class="fa fa-fw fa-cubes"></i>公账财务报表</a></li>
                    </ul>
                </li>
        </ul>
	</div>
	<!--左侧导航 结束-->

	<!--主体部分 开始-->
	<div class="main_box">
		<iframe src="{{ url('admin/info') }}" frameborder="0" width="100%" height="100%" name="main"></iframe>
	</div>
	<!--主体部分 结束-->

	<!--底部 开始-->
	<div class="bottom_box">
		CopyRight © 2018. Powered By <a href="javascript:;">Leo BBM</a>.
	</div>
	<!--底部 结束-->
	<script type="text/javascript">
		// 注销账号登出
		function loginOut() {
			loading();
			$.post('{{ url('admin/loginOut') }}',{
				"_token" : '{{ csrf_token() }}'
			},function (ret) {
				layer.close(loadingBox);
				var obj = $.parseJSON(ret);
				if (obj.status == 'success') {
					layer.msg(obj.msg);
					setTimeout(function () {
						location.href = '{{ url('admin/') }}';
					},1500);
				}else{
					layer.msg(obj.msg, function(){
					//关闭后的操作
					});
				}
			});
		}
        // 增加心跳帧检测session销毁情况
        // 轮询登录结果
    setInterval(function () {
        $.post('{{ url('admin/loginStatus') }}', {
            '_token' : '{{ csrf_token() }}'
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                console.log(obj.msg);
            }else{
                alert('登录状态过期,请刷新页面重新登录')
                setTimeout(function () {
                        location.href = '{{ url('admin') }}';
                    },1500);
            }
        })
    },60000);
	</script>
@endsection