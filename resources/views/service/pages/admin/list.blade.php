@extends('lib.service.header')
@section('body')
		<div class="weadmin-nav">
			<a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;z-index: 999;" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i>
			</a>
		</div>
		<div class="weadmin-body">
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
			  	<legend>用户列表</legend>
			</fieldset>
			<div class="weadmin-block">
				<button class="layui-btn" onclick="parent.tab.tabAdd('添加管理员','{{ url('service/admin/add') }}','admin-add')"><i class="layui-icon"></i> 添加管理员</button>
				<span class="fr" style="line-height:40px">共有管理员：{{ $admin->count() }} 人</span>
			</div>
			<table class="layui-table">
				<thead>
					<tr>
						<th>ID</th>
						<th>头像</th>
						<th>姓名</th>
						<th>手机</th>
						<th>部门</th>
						<th>权限</th>
						<th>加入时间</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($admin as $element)
					<tr>
						<td>{{ $element->id }}</td>
						<td><img style="display: inline-block;width: 2rem;border-radius: 50%;" src="{{ url($element->user_pic) }}"></td>
						<td>{{ $element->user_name ? $element->user_name : $element->user_nickname }}</td>
						<td>{{ $element->user_phone }}</td>
						<td>@switch($element->cate)
                                    @case(0)
                                        梦享家
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
                                        财务部门
                                        @break
                                    @case(6)
                                        融通四海
                                        @break
                                @endswitch</td>
						<td>@switch($element->rank)
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
                                        超级权限
                                @endswitch</td>
						<td>{{ $element->created_at }}</td>
							@if ($element->status)
								<td class="td-status">
									<a onclick="status({{ $element->id }})" href="javascript:;" title="启用">
										<span class="layui-btn layui-btn-normal layui-btn-xs">已启用</span>
									</a>
								</td>
							@else
								<td class="td-status">
									<a onclick="status({{ $element->id }})" href="javascript:;" title="启用">
										<span class="layui-btn layui-btn-danger layui-btn-xs">已停止</span>
									</a>
								</td>
							@endif
						<td class="td-manage">
							<a title="编辑" onclick="WeAdminShow('编辑','{{ url('service/admin/edit') }}/{{ $element->id }}')" href="javascript:;">
								<i class="layui-icon">&#xe642;</i>
							</a>
							<a title="删除" onclick="del({{ $element->id }})" href="javascript:;">
								<i class="layui-icon">&#xe640;</i>
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<div class="page">
				{{ $admin->links() }}
			</div>
		</div>
<script>
	function status(id) {
		loading();
		$.post('{{ url('service/admin/status') }}/'+id, {
			"_token" : '{{ csrf_token() }}',
		}, function(ret) {
			var obj = $.parseJSON(ret);
			layer.close(loadingBox);
			if (obj.status == 'success') {
				layer.msg(obj.msg, {icon: 1});
				setTimeout(function () {
					location.reload();
				},1500)
			}else{
				layer.msg(obj.msg, {icon: 2});
			}
		});
	}

	function del(id) {
		loading();
		$.post('{{ url('service/admin/del') }}/'+id, {
			"_token" : '{{ csrf_token() }}'
		}, function(ret) {
			var obj = $.parseJSON(ret);
			layer.close(loadingBox);
			if (obj.status == 'success') {
				layer.msg(obj.msg, {icon: 1});
				setTimeout(function () {
					location.reload();
				},1500)
			}else{
				layer.msg(obj.msg, {icon: 2});
			}
		});
	}
</script>
@endsection