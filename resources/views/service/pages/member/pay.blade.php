@extends('lib.service.header')
@section('body')
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
			<div class="layui-col-md10">
				<div class="layui-col-md2">
			    	<a href="javascript:;" class="layui-btn" onclick="parent.tab.tabAdd('会员信息编辑','http://localhost/service/user/info/{{ $user->user_uuid }}','user-info_{{ $user->user_uuid }}')">
					  	<i class="layui-icon">&#xe608;</i> 基本信息
					</a>
			  	</div>
				<div class="layui-col-md2">
			    	<a href="javascript:;" class="layui-btn" onclick="parent.tab.tabAdd('会员充值选项','http://localhost/service/user/pay/{{ $user->user_uuid }}','user-pay_{{ $user->user_uuid }}')">
					  	<i class="layui-icon">&#xe608;</i> 积分&会籍
					</a>
			  	</div>
			  	<div class="layui-col-md2">
			    	<a href="javascript:;" class="layui-btn" onclick="parent.tab.tabAdd('用户积分日志','http://localhost/service/user/log/{{ $user->user_uuid }}','user-log_{{ $user->user_uuid }}')">
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
			<div class="layui-row layui-col-space15">
				<div class="layui-col-md12">
			      	<div class="layui-card">
			        	<div class="layui-card-header">基本信息</div>
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

								<div class="layui-form-item">
								    <label class="layui-form-label">体验资格</label>
								    <div class="layui-input-inline">
								    	<input disabled="disabled" type="text" id="L_phone" name="userphone" value="@if ($user->temp_join)已经购买@else 未购买 @endif" lay-verify="required|nikename" autocomplete="off" class="layui-input">
								    </div>
								</div>
								<div class="layui-form-item">
									<label for="L_uid" class="layui-form-label">
					                  	<span class="we-red"></span>会籍变动:
					              	</label>
									<div class="layui-input-inline">
										<input type="text" value="{{ date('Y-m-d H:i:s',$user->rank_start) }}" id="L_uid" name="L_uid" autocomplete="off" class="layui-input">
									</div>
								</div>
							</form>
			        	</div>
			      	</div>
					<br>
			      	<div class="layui-card">
						<div class="layui-card-header">会籍变动</div>
						<div class="layui-card-body">
							<form class="layui-form layui-form-pane">
								<div class="layui-form-item">
							    	<label class="layui-form-label">新的会籍</label>
							    	<div class="layui-input-block">
							      		<input type="radio" name="rank" value="0" title="普通用户">
							      		<input type="radio" name="rank" value="1" @if ($user->temp_join)
							      			disabled=""
							      		@endif title="体验会籍">
							      		<input type="radio" name="rank" value="2" title="男爵会籍">
							      		<input type="radio" name="rank" value="3" title="子爵会籍">
							      		<input type="radio" name="rank" value="4" title="伯爵会籍">
							      		<input type="radio" name="rank" value="5" title="侯爵会籍">
							      		<input type="radio" name="rank" value="6" title="公爵会籍">
							      		<input type="radio" name="rank" value="9" title="内部员工">
							    	</div>
							  	</div>

								<div class="layui-form-item">
									<label for="log" class="layui-form-label">
								              <span class="we-red"></span>变动原因:
								          </label>
									<div class="layui-input-block">
										<input type="text" value="" id="rankLog" name="rankLog" placeholder="请填写变动的原因" lay-verify="nickname" autocomplete="off" class="layui-input">
									</div>
								</div>

								<div class="layui-form-item">
									<label for="date" class="layui-form-label">
								              变动时间:
								          </label>
									<div class="layui-input-inline">
							        	<input type="text" name="date" id="date" lay-verify="date" value="{{ date('Y-m-d') }}" autocomplete="off" class="layui-input">
							      	</div>
								</div>
								<div class="layui-form-item">
									<label for="admin" class="layui-form-label">
								              操作人员:
								          </label>
									<div class="layui-input-inline">
							        	<input disabled="disabled" type="text" id="admin" name="userphone" value="{{ session('admin')->user_name }}" lay-verify="required|nikename" autocomplete="off" class="layui-input">
							      	</div>
								</div>
								<div class="layui-form-item">
									<a class="layui-btn" onclick="updateRank()" lay-filter="add" lay-submit="">确定修改</a>
									<input type="hidden" name="dataId" id="dataId" value="" />
								</div>
							</form>
						</div>
					</div>
			    	<br>
			    	<div class="layui-card">
						<div class="layui-card-header">积分充值</div>
						<div class="layui-card-body">
							<form class="layui-form layui-form-pane">
								<div class="layui-form-item">
								    <div class="layui-inline">
								      	<label class="layui-form-label">基本积分</label>
								      	<div class="layui-input-inline">
								        	<input disabled="disabled" value="{{ $user->user_point }}" type="number" name="" autocomplete="off" class="layui-input">
								      	</div>
								    </div>
								    <div class="layui-inline">
								      	<label class="layui-form-label">增加积分</label>
								      	<div class="layui-input-inline">
								        	<input type="number" placeholder="增加的基本积分数量" name="user_point" autocomplete="off" class="layui-input">
								      	</div>
								    </div>
								</div>
								<hr class="layui-bg-green">
								<div class="layui-form-item">
								    <div class="layui-inline">
								      	<label class="layui-form-label">赠送积分</label>
								      	<div class="layui-input-inline">
								        	<input disabled="disabled" value="{{ $user->user_point_give }}" type="number" name="" autocomplete="off" class="layui-input">
								      	</div>
								    </div>
								    <div class="layui-inline">
								      	<label class="layui-form-label">增加积分</label>
								      	<div class="layui-input-inline">
								        	<input type="number" placeholder="增加的赠送积分数量" name="user_point_give" autocomplete="off" class="layui-input">
								      	</div>
								    </div>
								</div>
								<hr class="layui-bg-green">
								<div class="layui-form-item">
								    <div class="layui-inline">
								      	<label class="layui-form-label">开拓积分</label>
								      	<div class="layui-input-inline">
								        	<input disabled="disabled" value="{{ $user->user_point_open }}" type="number" name="" autocomplete="off" class="layui-input">
								      	</div>
								    </div>
								    <div class="layui-inline">
								      	<label class="layui-form-label">增加积分</label>
								      	<div class="layui-input-inline">
								        	<input type="number" placeholder="增加的开拓积分数量" name="user_point_open" autocomplete="off" class="layui-input">
								      	</div>
								    </div>
								</div>
								<hr class="layui-bg-green">
								<div class="layui-form-item">
									<label for="log" class="layui-form-label">
								              <span class="we-red"></span>变动原因:
								          </label>
									<div class="layui-input-block">
										<input type="text" value="" name="pointLog" placeholder="请填写变动的原因" lay-verify="nickname" autocomplete="off" class="layui-input">
									</div>
								</div>
								<div class="layui-form-item">
									<label for="admin" class="layui-form-label">
								              操作人员:
								          </label>
									<div class="layui-input-inline">
							        	<input disabled="disabled" type="text" id="admin" name="userphone" value="{{ session('admin')->user_name }}" lay-verify="required|nikename" autocomplete="off" class="layui-input">
							      	</div>
								</div>
								<div class="layui-form-item">
									<a class="layui-btn" onclick="updatePoint()" lay-filter="add" lay-submit="">确定修改</a>
									<input type="hidden" name="dataId" id="dataId" value="" />
								</div>
							</form>
						</div>
					</div>






			    </div>
			</div>
		</div>
	<script type="text/javascript">
		layui.extend({
			admin: '{/}{{ url('static/js') }}/admin'
		});
		layui.use(['form','laydate'], function(){
			var form = layui.form;
			form.on('select(province)', function(data){
				// 获取当前身份下的市区
				console.log(data)
				getCity(data.value)
			});
			laydate = layui.laydate;
			  //日期
			laydate.render({
			    elem: '#date'
			});
		});

		function updateRank() {
			var rank = $("input[name='rank']:checked").val();
			var log = $("input[name='rankLog']").val();
			var date = $("input[name='date']").val();
			console.log(rank);
			console.log(log);
			console.log(date);
			if (rank || log || rank) {
				loading()
				$.post('{{ url('service/user/rank/change') }}', {
					"_token" : '{{ csrf_token() }}',
					"uuid" : '{{ $user->user_uuid }}',
					"rank" : rank,
					"date" : date,
					"log" : log
				}, function(ret) {
					layer.close(loadingBox);
					var obj = $.parseJSON(ret);
					if (obj.status == 'success') {
						layer.msg(obj.msg);
						setTimeout(function () {
							location.reload();
						}, 1000)
					}else{
						layer.msg(obj.msg, function(){
							//关闭后的操作
						});
					}
				});
			}else{
				layer.msg('请勿提交空数据,请检查未填写的数据', function(){
					//关闭后的操作
				});
			}
		}

		function updatePoint() {
			var point = $("input[name='user_point']").val();
			var point_give = $("input[name='user_point_give']").val();
			var point_open = $("input[name='user_point_open']").val();
			var log = $("input[name='pointLog']").val();
			console.log(point);
			console.log(point_give);
			console.log(point_open);
			console.log(log);
			if (point || point_give || point_open && log) {
				loading();
				$.post('{{ url('service/user/point/change') }}', {
					"_token" : '{{ csrf_token() }}',
					"uuid" : '{{ $user->user_uuid }}',
					"point" : point,
					"point_give" : point_give,
					"point_open" : point_open,
					"log" : log
				}, function(ret) {
					layer.close(loadingBox)
					var obj = $.parseJSON(ret);
					if (obj.status == 'success') {
						layer.msg(obj.msg);
						setTimeout(function () {
							location.reload();
						}, 1000)
					}else{
						layer.msg(obj.msg, function(){
							//关闭后的操作
						});
					}
				});
			}else{
				layer.msg('请勿提交空数据,请检查未填写的数据', function(){
					//关闭后的操作
				});
			}
		}
	</script>
@endsection