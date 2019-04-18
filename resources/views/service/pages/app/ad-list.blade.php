@extends('lib.service.header')
@section('body')
		<a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;z-index: 999;" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i></a>
		<div class="weadmin-body">
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
			  	<legend>APP Ad Manage</legend>
			</fieldset>
			<div class="weadmin-block">
				<button class="layui-btn" onclick="WeAdminShow('添加广告','{{ url('service/app/ad/add') }}',600,400)"><i class="layui-icon"></i>添加广告</button>
				<span class="fr" style="line-height:40px">共有数据：{{ $ad->count() }} 条</span>
			</div>
			<table class="layui-table" id="memberList">
				<thead>
					<tr>
						<th>ID序</th>
						<th>缩略图</th>
						<th>OSS地址</th>
						<th>链接地址</th>
						<th>点击量</th>
						<th>创建时间</th>
						<th>修改时间</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody id="table">
					@foreach ($ad as $element)
						<tr data-id="{{ $element->id }}">
							<td>{{ $element->id }}</td>
							<td><img style="display: inline-block;width: 2rem;border-radius: 50%;" src="{{ url($element->img) }}"></td>
							<td>{{ $element->img }}</td>
							<td>{{ $element->link }}</td>
							<td>暂未统计</td>
							<td>{{ $element->created_at }}</td>
							<td>{{ $element->updated_at }}</td>
							<td class="td-status">
								@if ($element->status)
									<span class="layui-btn layui-btn-normal layui-btn-xs" onclick="status({{ $element->id }})">正常</span>
								@else
									<span class="layui-btn layui-btn-danger layui-btn-xs" onclick="status({{ $element->id }})">停止</span>
								@endif
							</td>
							<td class="td-manage">
								<a href="javascript:;"  onclick="WeAdminShow('编辑广告','{{ url('service/app/ad/edit') }}/{{ $element->id }}',600,400)">
									<i class="layui-icon">&#xe642;</i>
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
	function status(id) {
		loading();
		$.post('{{ url('service/app/ad/status') }}/'+id, {
			"_token" : '{{ csrf_token() }}'
		}, function(ret) {
			layer.close(loadingBox)
			var obj = $.parseJSON(ret);
			if (obj.status == 'success') {
				layer.msg(obj.msg);
				location.reload();
			}else{
				layer.msg(obj.msg, function () {
					// body...
				})
			}
		});
	}
</script>
@endsection