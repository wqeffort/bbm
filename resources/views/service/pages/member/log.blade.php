@extends('lib.service.header')
@section('body')
<style>
.layui-form-pane .layui-form-label {
	width: 150px;
}
.gap {
	background: #f2f2f2;
    padding: .5rem;
    color: #CCC;
}
th,td {
	text-align: center;
	font-size: .8rem;
	white-space: nowrap;
}
p {
	font-size: .8rem;
	white-space: nowrap;
}
</style>
		{{-- <div class="weadmin-nav">
			<a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i></a>
		</div> --}}
		<a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;z-index: 999;" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i></a>
		<div class="weadmin-body">
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
			  <legend>积分&会籍</legend>
			</fieldset>
			<div class="layui-row layui-col-space10">
				<div class="layui-col-md10">
					<div class="layui-col-md2">
				    	<a href="javascript:;" class="layui-btn" onclick="parent.tab.tabAdd('会员信息编辑','http://localhost/service/user/info/{{ $user->user_uuid }}','user-info_{{ $user->user_uuid }}')">
						  	<i class="layui-icon">&#xe608;</i> 基本信息
						</a>
				  	</div>
					<div class="layui-col-md2">
				    	<a href="javascript:;" class="layui-btn" onclick="parent.tab.tabAdd('会员充值选项','http://localhost/service/user/pay/{{ $user->user_uuid }}','user-log_{{ $user->user_uuid }}')">
						  	<i class="layui-icon">&#xe608;</i> 积分&会籍
						</a>
				  	</div>
				  	<div class="layui-col-md2">
				    	<a href="javascript:;" class="layui-btn">
						  	<i class="layui-icon">&#xe608;</i> 用户日志
						</a>
				  	</div>
				  	<div class="layui-col-md2">
					    <a href="javascript:;" class="layui-btn">
						  	<i class="layui-icon">&#xe608;</i> 解除微信
						</a>
				  	</div>
				  	<div class="layui-col-md2">
				    	<a href="javascript:;" class="layui-btn">
						  	<i class="layui-icon">&#xe608;</i> 解除实名
						</a>
				  	</div>
				</div>
			</div>
			<br>
			<div class="layui-col-md12">
				<div class="layui-card-header">基本信息</div>
				<div class="layui-col-md4">
					<div class="layui-card">
				        <div class="layui-card-body">
				          	<form class="layui-form layui-form-pane">
								<div class="layui-form-item">
									<label for="L_name" class="layui-form-label">
										<span class="we-red"></span>用户姓名:
									</label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="text" value="{{ $user->user_name }}" id="L_name" name="name" lay-verify="required|name" autocomplete="" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label for="L_nickname" class="layui-form-label">
										<span class="we-red"></span>用户昵称:
									</label>
									<div class="layui-input-inline">
										<input  disabled="disabled" type="text" value="{{ $user->user_nickname }}" id="L_nickname" name="nickname" lay-verify="nickname" autocomplete="off" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label for="L_phone" class="layui-form-label">
										用户账号:
									</label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="number" id="L_phone" name="userphone" value="{{ $user->user_phone }}" lay-verify="required|nikename" autocomplete="off" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label class="layui-form-label">当前等级:</label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="text" id="L_phone" name="userphone" value="@switch($user->user_rank)
										@case(0)普通用户@break
										@case(1)体验会籍@break
										@case(2)男爵会籍@break
										@case(3)子爵会籍@break
										@case(4)伯爵会籍@break
										@case(5)侯爵会籍@break
										@case(6)公爵会籍@break
										@default内部会员
										@endswitch" lay-verify="required|nikename" autocomplete="off" class="layui-input">
									</div>
								</div>
							</form>
				        </div>
					</div>
				</div>
				<div class="layui-col-md4">
					<div class="layui-card">
				        <div class="layui-card-body">
				          	<form class="layui-form layui-form-pane">
								<div class="layui-form-item">
									<label for="L_name" class="layui-form-label">
										<span class="we-red"></span>用户普通积分:
									</label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="number" value="{{ $user->user_point }}" id="L_name" name="name" lay-verify="required|name" autocomplete="" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label for="L_nickname" class="layui-form-label">
										<span class="we-red"></span>用户赠送积分:
									</label>
									<div class="layui-input-inline">
										<input  disabled="disabled" type="text" value="{{ $user->user_point_give }}" id="L_nickname" name="nickname" lay-verify="nickname" autocomplete="off" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label for="L_uid" class="layui-form-label">
						                <span class="we-red"></span>用户开拓积分:
						            </label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="number" value="{{ $user->user_point_open }}" id="L_uid" name="L_uid" autocomplete="off" class="layui-input">
									</div>
								</div>
								<div class="layui-form-item">
									<label for="L_nickname" class="layui-form-label">
										<span class="we-red"></span>加盟状态:
									</label>
									@if ($join)
										<div class="layui-input-block">
									      <input type="checkbox" disabled="disabled"  checked="" name="open" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
									    </div>
									@else
										<div class="layui-input-block">
									      <input type="checkbox" disabled="disabled"  name="close" lay-skin="switch" lay-text="ON|OFF">
									    </div>
									@endif
								</div>
							</form>
				        </div>
					</div>
				</div>
				@if ($join)
				<div class="layui-col-md4">
					<div class="layui-card">
				        <div class="layui-card-body">
				          	<form class="layui-form layui-form-pane">
								<div class="layui-form-item">
									<label for="L_name" class="layui-form-label">
										<span class="we-red"></span>加盟商普通积分:
									</label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="number" value="{{ $join->point }}" id="L_name" name="name" lay-verify="required|name" autocomplete="" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label for="L_nickname" class="layui-form-label">
										<span class="we-red"></span>加盟商赠送积分:
									</label>
									<div class="layui-input-inline">
										<input  disabled="disabled" type="text" value="{{ $join->point_give }}" id="L_nickname" name="nickname" lay-verify="nickname" autocomplete="off" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label for="L_uid" class="layui-form-label">
						                <span class="we-red"></span>加盟商返佣积分:
						            </label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="number" value="{{ $join->point_fund }}" id="L_uid" name="L_uid" autocomplete="off" class="layui-input">
									</div>
								</div>
								<div class="layui-form-item">
									<label for="L_nickname" class="layui-form-label">
										<span class="we-red"></span>梦享家现金账户:
									</label>
									<div class="layui-input-inline">
										<input disabled="disabled" type="number" value="{{ $join->join_cash }}" id="L_uid" name="L_uid" autocomplete="off" class="layui-input">
									</div>
								</div>
							</form>
				        </div>
					</div>
				</div>
				@endif
			</div>
			<div class="layui-row layui-col-space10">
				<div class="layui-col-md10">
					<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
						<ul class="layui-tab-title">
							<li class="layui-this">用户积分</li>
							<li>商城订单</li>
							<li>加盟商积分</li>
							<li>春蚕流水</li>
							<li>梦享家现金</li>
							<li>债权收益</li>
						</ul>
						<a href="javascript:;" id="getLog" onclick="getLog(0)">
							<p id="showLog" style="text-align: center;margin-top: 2rem;padding: 1rem;background: #009588;color: #FFF;"><i class="fa fa-search"></i> 点击查看积分日志</p>
						</a>
						<div class="layui-tab-content" id="log_cont"></div>
						<div class="layui-tab-content" id="log_num"></div>
					</div>
				</div>
			</div>
		</div>
	<script type="text/javascript">
		var log0 = '';
		var log1 = '';
		var log2 = '';
		var log3 = '';
		var log4 = '';
		var log5 = '';
		layui.extend({
			admin: '{/}{{ url('static/js') }}/admin'
		});
		layui.use(['form','laypage','element'], function(){
  			var element = layui.element;
			var form = layui.form;
			var laypage = layui.laypage;
			// tab callBack
			element.on('tab(docDemoTabBrief)', function(data){
			  	console.log(this); //当前Tab标题所在的原始DOM元素
			  	console.log(data.index); //得到当前Tab的所在下标
			  	console.log(data.elem); //得到当前的Tab大容器
			  	getLog(data.index);
			});
		});


		function getLog(id) {
			$('#getLog').hide();
			switch(id) {
				case 0:
					// 检查变量log是否被赋值,防止重复请求;
					if (log0) {
						// 直接解析log
						handleLog(log0)
					}else{
						// 发送请求.
						loading();
						$.post('{{ url('service/user/get/userPoint/log') }}/{{ $user->user_uuid }}',{
							"_token" : '{{ csrf_token() }}'
						},function (ret) {
							layer.close(loadingBox);
							var obj = $.parseJSON(ret);
							if (obj.status == 'success') {
								if (obj.data.length > 0) {
									log0 = obj.data;
									handleLog(log0,id)
								}else{
									layer.msg('用户日志数据为空!', {icon: 2});
								}
							}else{
								layer.msg(obj.msg, {icon: 2});
							}
						});
					}
				break;
				case 1:
					if (log1) {
						handleLogOrder(log1);
					}else{
						loading();
						$.post('{{ url('service/user/log/get/order') }}/{{ $user->user_uuid }}', {
							"_token" : '{{ csrf_token() }}'
						}, function(ret) {
							var obj = $.parseJSON(ret);
							layer.close(loadingBox)
							if (obj.status == 'success') {
								if (obj.data.length > 0) {
									log1 = obj.data;
									// console.log(log1)
									handleLogOrder(log1,id)
								}
							}else{
								layer.msg(obj.msg, {icon: 2});
							}
						});
					}
				break;
				case 2:
					// 获取加盟商日志
					if (log2) {
						handleLogJoin(log2,id)
					}else{
						loading();
						$.post('{{ url('service/join/log/get/point') }}/{{ $user->user_uuid }}', {
							"_token" : '{{ csrf_token() }}'
						}, function(ret) {
							var obj = $.parseJSON(ret);
							layer.close(loadingBox)
							if (obj.status == 'success') {
								if (obj.data.length > 0) {
									log2 = obj.data;
									// console.log(log1)
									handleLogJoin(log2,id)
								}
							}else{
								layer.msg(obj.msg, {icon: 2});
							}
						});
					}
				break;
				case 3:
					// 获取春蚕日志
					if (log3) {
						handleLogSping(log3,id)
					}else{
						loading();
						$.post('{{ url('service/join/log/get/spring') }}/{{ $user->user_uuid }}', {
							"_token" : '{{ csrf_token() }}'
						}, function(ret) {
							var obj = $.parseJSON(ret);
							layer.close(loadingBox)
							if (obj.status == 'success') {
								if (obj.data.length > 0) {
									log3 = obj.data;
									// console.log(log1)
									handleLogSping(log3,id)
								}
							}else{
								layer.msg(obj.msg, {icon: 2});
							}
						});
					}
				break;
				case 4:
				break;
				case 5:
				break;
			}
		}


		function handleLog(log,id) {
			// console.log(log)
			layui.laypage.render({
				elem: 'log_num'
				,count: log.length
				,jump: function(res) {
					var cont = '';
					$('#log_cont').html(function(){
						thisData = log.concat().splice(res.curr*res.limit - res.limit, res.limit);
					    layui.each(thisData, function(index, val){
					    	if (val.point == null) {
					    		val.point = 0;
					    	}
					    	if (val.point_give == null) {
					    		val.point_give = 0;
					    	}
					    	if (val.point_open == null) {
					    		val.point_open = 0;
					    	}
					    	if (val.new_point_open == null) {
					    		val.new_point_open = 0;
					    	}
					    	if (val.mark == null) {
					    		val.mark = '';
					    	}
					    	if (val.add == 1) {
					    		style = "color:#C40000;";
					    	}else{
					    		style = "color:#009688;";
					    	}
					    	point_gap = val.new_point - val.point;
					    	point_give_gap = val.new_point_give - val.point_give;
					    	point_open_gap = val.new_point_open - val.point_open;
					       	cont += '<tr>\
								<td><p>'+val.id+'</p></td>\
								<td><p>'+val.type+'</p></td>\
								<td><p>'+val.point+' <i class="gap">('+point_gap+')</i> <span style="'+style+'">'+val.new_point+'</span></p></td>\
								<td><p>'+val.point_give+' <i class="gap">('+point_give_gap+')</i> <span style="'+style+'">'+val.new_point_give+'</span></p></td>\
								<td><p>'+val.point_open+' <i class="gap">('+point_open_gap+')</i> <span style="'+style+'">'+val.new_point_open+'</span></p></td>\
								<td><p>'+val.created_at+'</p></td>\
								<td><p>'+val.mark+'</p></td>\
							</tr>'
					    });
					    var html = '<div class="layui-form">\
							<table class="layui-table">\
								<thead>\
									<tr>\
										<th>日志ID</th>\
										<th>变动原因</th>\
										<th>普通积分</th>\
										<th>赠送积分</th>\
										<th>开拓积分</th>\
										<th>变动时间</th>\
										<th>备注记录</th>\
									</tr>\
								</thead>\
								<tbody>'+cont+'</tbody>\
							</table>\
						</div>';
					   	return html
					}());
				}
			});
		}

		function handleLogOrder(log, id) {
			console.log(log)
			layui.laypage.render({
				elem: 'log_num'
				,count: log.length
				,jump: function(res) {
					var cont = '';
					$('#log_cont').html(function(){
						thisData = log.concat().splice(res.curr*res.limit - res.limit, res.limit);
					    $.each(thisData, function(index, val){
					    	orderGoods = ''
					    	time = ''
					    	// console.log(val)
					    	$.each(val.order, function(i, v) {
					    		// console.log(v.goods_name);
					    		// console.log(v.num)
					    		orderGoods += '<p style="white-space: unset;">'+v.goods_name+'<span>'+v.goods_attr+'</span><b> * </b>'+v.goods_num+'</p>';
					    		time = v.created_at
					    	});
					    	// console.log(orderGoods);
					    	switch(val.type){
					    		case 1:
					    			type = '微信支付'
					    		break;
					    		case 2:
					    			type = '积分支付'
					    		break;
					    		case 3:
					    			type = '支付宝支付'
					    		break;
					    	}

					    	if (val.express) {
					    		var status = '已经发货';
					    	}else{
					    		var status = '暂未发货';
					    	}
					       	cont += '<tr>\
								<td><p>'+val.id+'</p></td>\
								<td><p>'+val.num+'</p></td>\
								<td>'+orderGoods+'</td>\
								<td><p>'+val.total+'</p></td>\
								<td><p>'+type+'</p></td>\
								<td><a href="javascript:layer.tips('+val.express+');">'+status+'</a></td>\
								<td><p>'+val.mark+'</p></td>\
								<td><p>'+time+'</p></td>\
					       	</tr>'
					    });
					    var html = '<div class="layui-form">\
							<table class="layui-table">\
								<thead>\
									<tr>\
										<th>日志ID</th>\
										<th>订单编号</th>\
										<th>订单详情</th>\
										<th>订单总额</th>\
										<th>支付方式</th>\
										<th>订单状态</th>\
										<th>订单备注</th>\
										<th>订单时间</th>\
									</tr>\
								</thead>\
								<tbody>'+cont+'</tbody>\
							</table>\
						</div>';
					   	return html
					}());
				}
			});
		}

		function handleLogJoin(log,id) {
			// console.log(log);
			layui.laypage.render({
				elem: 'log_num'
				,count: log.length
				,jump: function(res) {
					var cont = '';
					$('#log_cont').html(function(){
						thisData = log.concat().splice(res.curr*res.limit - res.limit, res.limit);
					    layui.each(thisData, function(index, val){
					    	if (val.point == null) {
					    		val.point = 0;
					    	}
					    	if (val.point_give == null) {
					    		val.point_give = 0;
					    	}
					    	if (val.point_open == null) {
					    		val.point_open = 0;
					    	}
					    	if (val.new_point_open == null) {
					    		val.new_point_open = 0;
					    	}
					    	if (val.new_point_fund == null) {
					    		val.new_point_fund = 0;
					    	}
					    	if (val.mark == null) {
					    		val.mark = '';
					    	}
					    	if (val.add == 1) {
					    		style = "color:#C40000;";
					    	}else{
					    		style = "color:#009688;";
					    	}
					    	point_gap = val.new_point - val.point;
					    	point_give_gap = val.new_point_give - val.point_give;
					    	point_open_gap = val.new_point_open - val.point_open;
					    	point_fund_gap = val.new_point_fund - val.point_fund;
					    	if (val.user_name) {
					    		name = val.user_name;
					    	}else{
					    		name = val.user_nickname;
					    	}
					       	cont += '<tr>\
								<td><p>'+val.id+'</p></td>\
								<td><p>'+val.type+'</p></td>\
								<td><p>'+val.point+' <i class="gap">('+point_gap+')</i> <span style="'+style+'">'+val.new_point+'</span></p></td>\
								<td><p>'+val.point_give+' <i class="gap">('+point_give_gap+')</i> <span style="'+style+'">'+val.new_point_give+'</span></p></td>\
								<td><p>'+val.point_fund+' <i class="gap">('+point_fund_gap+')</i> <span style="'+style+'">'+val.new_point_fund+'</span></p></td>\
								<td><p>'+val.point_open+' <i class="gap">('+point_open_gap+')</i> <span style="'+style+'">'+val.new_point_open+'</span></p></td>\
								<td><p>'+val.created_at+'</p></td>\
								<td><p>'+name+'('+val.user_phone+')</p></td>\
								<td><p>'+val.mark+'</p></td>\
							</tr>'
					    });
					    var html = '<div class="layui-form">\
							<table class="layui-table">\
								<thead>\
									<tr>\
										<th>日志ID</th>\
										<th>变动原因</th>\
										<th>普通积分</th>\
										<th>赠送积分</th>\
										<th>返佣积分</th>\
										<th>开拓积分</th>\
										<th>变动时间</th>\
										<th>关联用户</th>\
										<th>备注记录</th>\
									</tr>\
								</thead>\
								<tbody>'+cont+'</tbody>\
							</table>\
						</div>';
					   	return html
					}());
				}
			});
		}

		function handleLogSping(log,id) {
			layui.laypage.render({
				elem: 'log_num'
				,count: log.length
				,jump: function(res) {
					var cont = '';
					$('#log_cont').html(function(){
						thisData = log.concat().splice(res.curr*res.limit - res.limit, res.limit);
					    layui.each(thisData, function(index, val){
					    	if (val.point == null) {
					    		val.point = 0;
					    	}
					    	if (val.mark == null) {
					    		val.mark = '';
					    	}
					    	if (val.add == 1) {
					    		style = "color:#C40000;";
					    	}else{
					    		style = "color:#009688;";
					    	}
					    	point_gap = val.new_point - val.point;
					       	cont += '<tr>\
								<td><p>'+val.id+'</p></td>\
								<td><p>'+val.type+'</p></td>\
								<td><p>'+val.point+' <i class="gap">('+point_gap+')</i> <span style="'+style+'">'+val.new_point+'</span></p></td>\
								<td><p>'+val.created_at+'</p></td>\
							</tr>'
					    });
					    var html = '<div class="layui-form">\
							<table class="layui-table">\
								<thead>\
									<tr>\
										<th>日志ID</th>\
										<th>变动原因</th>\
										<th>春蚕积分</th>\
										<th>变动时间</th>\
									</tr>\
								</thead>\
								<tbody>'+cont+'</tbody>\
							</table>\
						</div>';
					   	return html
					}());
				}
			});
		}
	</script>
@endsection
