@extends('lib.service.header')
@section('body')
	<body>
		<a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;z-index: 999;" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i></a>
		<div class="weadmin-body">
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
			  	<legend>加盟商列表</legend>
			</fieldset>
			<div class="layui-row">
				<form class="layui-form layui-col-md12 we-search">
					用户搜索：
					<div class="layui-inline">
						<input type="text" name="search" placeholder="请随意输入" autocomplete="off" class="layui-input">
					</div>
					<a href="javascript:;" class="layui-btn" lay-submit="" lay-filter="sreach" onclick="searchContent()"><i class="layui-icon">&#xe615;</i></a>
				</form>
			</div>
			<div class="weadmin-block">
				<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
				<button class="layui-btn" onclick="WeAdminShow('添加加盟商','./add.html',600,400)"><i class="layui-icon"></i>添加加盟商</button>
				<span class="fr" style="line-height:40px">共有数据：20 条</span>
			</div>
			<table class="layui-table" id="memberList">
				<thead>
					<tr>
						<th>
							<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
						</th>
						<th>身份编码</th>
						<th>头像</th>
						<th>姓名</th>
						<th>昵称</th>
						<th>手机</th>
						<th>会籍</th>
						<th>加盟时间</th>
						<th>加盟状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody id="table">
					@foreach ($data as $element)
						<tr data-id="{{ $element->id }}">
							<td>
								<div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="{{ $element->id }}"><i class="layui-icon">&#xe605;</i></div>
							</td>
							<td>{{ $element->user_uuid }}</td>
							<td><img style="display: inline-block;width: 2rem;border-radius: 50%;" src="{{ url($element->user_pic) }}"></td>
							<td>{{ $element->user_name }}</td>
							<td>{{ $element->user_nickname }}</td>
							<td>{{ $element->user_phone }}</td>
							<td>@switch($element->user_rank)
							    @case(0)
							        普通用户
							        @break
								@case(1)
							        体验会籍
							        @break
							    @case(2)
							        男爵会籍
							        @break
							    @case(3)
							        子爵会籍
							        @break
								@case(4)
							        伯爵会籍
							        @break
							    @case(5)
							        侯爵会籍
							        @break
							    @case(6)
							        公爵会籍
							        @break
							    @case(8)
							        内部员工
							        @break
							    @case(9)
							        内部员工
							        @break
							    @case(10)
							        内部员工
							        @break
							    @default
							        未知等级
							@endswitch
							</td>
							<td>{{ $element->created_at }}</td>
							<td class="td-status">
								@if ($element->status)
									<span class="layui-btn layui-btn-normal layui-btn-xs">正常</span>
								@else
									<span class="layui-btn layui-btn-danger layui-btn-xs">停止</span>
								@endif
							</td>							<td class="td-manage">
								<a href="javascript:;" title="编辑" target="tabAdd" _href="{{ url('service/join/info') }}/{{ $element->user_uuid }}" onclick="parent.tab.tabAdd('会员信息编辑','{{ url('service/join/info') }}/{{ $element->user_uuid }}','user-info_{{ $element->user_uuid }}')">
									<i class="layui-icon">&#xe642;</i>
								</a>
								<a href="javascript:;" title="充值" onclick="parent.tab.tabAdd('会员充值选项','{{ url('service/join/pay') }}/{{ $element->user_uuid }}','user-pay_{{ $element->user_uuid }}')">
									<i class="layui-icon">&#xe65e;</i>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
{{-- 			<div class="page">
				<div>
					<a class="prev" href="">&lt;&lt;</a>
					<a class="num" href="">1</a>
					<span class="current">2</span>
					<a class="num" href="">3</a>
					<a class="num" href="">489</a>
					<a class="next" href="">&gt;&gt;</a>
				</div>
			</div> --}}
		</div>

<script type="text/javascript">
	// 搜索功能
	function searchContent() {
		loading();
		// 获取搜索框中的值
		var text = $('input[name=search]').val();
		if (text) {
			$.post('{{ url('service/search/join') }}', {
				'_token' : '{{ csrf_token() }}',
				'text' : text
			}, function (ret) {
				var obj = $.parseJSON(ret);
				layer.close(loadingBox);
				if (obj.status == 'success') {
					var html = '';
					$.each(obj.data, function(index, val) {
						if (!val.user_pic || val.user_pic == 'null') {
							var pic = '{{ url('img/logo_m.png') }}'
						}else{
							var pic = val.user_pic
						}
						html += '<tr data-id="'+val.id+'">\
						<td>\
							<div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='+val.id+'><i class="layui-icon">&#xe605;</i></div>\
						</td>\
						<td>'+val.user_uuid+'</td>\
						<td><img style="display: inline-block;width: 2rem;border-radius: 50%;" src="'+pic+'"></td>\
						<td>'+val.user_name+'</td>\
						<td>'+val.user_nickname+'</td>\
						<td>'+val.user_phone+'</td>\
						<td>'+isRank(val.user_rank)+'</td>\
						<td>'+val.created_at+'</td>\
						<td>'+val.updated_at+'</td>\
						<td class="td-status">\
							<span class="layui-btn layui-btn-normal layui-btn-xs">正常</span></td>\
						<td class="td-manage">\
							<a href="javascript:;" title="编辑" target="tabAdd" _href="{{ url('service/join/info') }}/'+val.user_uuid+'" onclick=parent.tab.tabAdd("会员信息编辑","{{ url('service/join/info') }}/'+val.user_uuid+'","user-info_'+val.user_uuid+'")>\
									<i class="layui-icon">&#xe642;</i>\
								</a>\
							<a href="javascript:;" title="充值" target="tabAdd" _href="{{ url('service/join/pay') }}/'+val.user_uuid+'" onclick=parent.tab.tabAdd("会员充值选项","{{ url('service/join/pay') }}/'+val.user_uuid+'","user-pay_'+val.user_uuid+'")>\
								<i class="layui-icon">&#xe65e;</i>\
							</a>\
						</td>\
					</tr>'
					});
					$('#table').html(html);
				}else{
					layer.msg(obj.msg, {icon: 2});
				}
			})
		}else{
			layer.msg('搜索的值不能为空!', {icon: 2});
		}
	}
</script>
@endsection