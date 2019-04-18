@extends('lib.service.header')
@section('body')
<style type="text/css">
	body {
		background: #FEFEFE;
	}
	dt {
            text-align: center;
            background: #C40000;
            color: #FFF;
            padding: .7rem 0;
            font-size: 1rem;
        }
        dd {
            margin: 1rem;
            border-bottom: 1px solid #999;
            display: flex;
            justify-content: space-between;
        }

    /*地区三级联动列表*/
s {
	position:absolute;
	top:1px;
	right:0;
	width:32px;
	height:32px;
	background:url("/../../images/arrow.png") no-repeat;
}
._citys {
 	/*width: 450px;*/
  	display: inline-block;
  	border: 2px solid #eee;
  	padding: 5px;
  	position: relative;
}
._citys span {
 	color: #009688;
  	height: 15px;
  	width: 15px;
  	line-height: 15px;
  	text-align: center;
  	border-radius: 3px;
  	position: absolute;
  	right: 10px;
  	top: 10px;
  	border: 1px solid #009688;
  	cursor: pointer;
 }
._citys0 {
 	width: 95%;
  	height: 34px;
  	line-height: 34px;
  	display: inline-block;
  	border-bottom: 2px solid #009688;
  	padding: 0px 5px;
  	font-size:14px;
  	font-weight:bold;
  	margin-left:6px;
}
._citys0 li {
 	display: inline-block;
  	line-height: 34px;
  	font-size: 15px;
  	color: #888;
  	width: 80px;
  	text-align: center;
  	cursor: pointer;
}
._citys1 {
 	width: 100%;
  	display: inline-block;
  	padding: 10px 0;
}
._citys1 a {
 	width: 83px;
  	height: 35px;
  	display: inline-block;
  	background-color: #f5f5f5;
  	color: #666;
  	margin-left: 6px;
  	margin-top: 3px;
  	line-height: 35px;
  	text-align: center;
  	cursor: pointer;
  	font-size: 12px;
  	border-radius: 5px;
  	overflow: hidden;
}
._citys1 a:hover {
 	color: #fff;
  	background-color: #009688;
}
.AreaS {
 	background-color: #009688 !important;
  	color: #fff !important;
}
#PoPy {
	bottom: 0;
	left: 0;
	/*top: 2.5rem;*/
}
.adsList dd {
	padding: 1rem;
}
.adsList ul {
	display: flex;
    justify-content: space-between;
    margin-bottom: .5rem;
    font-size: 1rem;
}
.adsList p {
	border-bottom: 1px solid #666;
    padding-bottom: .2rem;
    margin-bottom: .5rem;
    color: #666;
}
.addAdsBtn {
	text-align: left;
    background: #C40000;
    color: #FFF;
    font-size: 1rem;
    font-weight: 200;
    height: 2rem;
    line-height: 2rem;
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: .5rem 0;
}
.addAdsBtn ul {
	display: flex;
    justify-content: space-around;
    color: #FFF;

}
</style>
<div class="loading">
	<img style="width: 100%;height: 100%;" src="{{ url('img/load.gif') }}">
</div>
	{{-- 操作面板 --}}
	<a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;z-index: 999;" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i></a>
	<div class="weadmin-body">
		{{-- 快捷操作面板 --}}
		<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
		  <legend>用户信息</legend>
		</fieldset>
		<div class="layui-row layui-col-space10">
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
			<div class="layui-card">
	  			<div class="layui-card-header">User 基本信息设置</div>
	  			<div class="layui-card-body">
					<form class="layui-form layui-form-pane">
						<div class="layui-form-item">
							<label for="L_phone" class="layui-form-label">
				                用户账号:
				            </label>
							<div class="layui-input-inline">
								<input type="number" id="L_phone" name="userphone" value="{{ $user->user_phone }}" lay-verify="required|nikename" autocomplete="off" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
						    <label class="layui-form-label">用户性别:</label>
						    <div class="layui-input-block">
						    	@if ($user->user_sex < 2)
						    		<input type="radio" name="L_sex" value="1" title="男" checked="checked">
						      		<input type="radio" name="L_sex" value="2" title="女">
						      	@else
						      		<input type="radio" name="L_sex" value="1" title="男">
						      		<input type="radio" name="L_sex" value="2" title="女" checked="checked">
						    	@endif
						    </div>
						</div>

						<div class="layui-form-item">
							<label for="L_name" class="layui-form-label">
				                <span class="we-red"></span>用户姓名:
				            </label>
							<div class="layui-input-inline">
								<input type="text" value="{{ $user->user_name }}" id="L_name" name="name" lay-verify="required|name" autocomplete="" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_nickname" class="layui-form-label">
				                <span class="we-red"></span>用户昵称:
				            </label>
							<div class="layui-input-inline">
								<input type="text" value="{{ $user->user_nickname }}" id="L_nickname" name="nickname" lay-verify="nickname" autocomplete="off" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
						    <label class="layui-form-label">会员等级:</label>
						    <div class="layui-input-inline">
							    <select id="L_rank" lay-filter="L_rank" lay-verify="required">
							    	@switch($user->user_rank)
							    	    @case(0)
							    	        <option value="0" selected="">普通用户</option>
							    	        @break
							    		@case(1)
							    	        <option value="1" selected="">体验会籍</option>
							    	        @break
							    	    @case(2)
							    	        <option value="2" selected="">男爵会籍</option>
							    	        @break
							    		@case(3)
							    	        <option value="3" selected="">子爵会籍</option>
							    	        @break
							    	    @case(4)
							    	        <option value="4" selected="">伯爵会籍</option>
							    	        @break
							    		@case(5)
							    	        <option value="5" selected="">侯爵会籍</option>
							    	        @break
							    	    @case(6)
							    	        <option value="6" selected="">公爵会籍</option>
							    	        @break
							    		@case(10)
							    	        <option value="10" selected="">内部员工</option>
							    	        @break
							    	    @default
							    	        <option value="10" selected="">内部员工</option>
							    	@endswitch
							        <option value="0">普通用户</option>
							        <option value="1">体验会籍</option>
							        <option value="2">男爵会籍</option>
							        <option value="3">子爵会籍</option>
							        <option value="4">伯爵会籍</option>
							        <option value="5">侯爵会籍</option>
							        <option value="6">公爵会籍</option>
							        <option value="10">内部员工</option>
							    </select>
						    </div>
						</div>
						<div class="layui-form-item">
						    <label class="layui-form-label">体验资格</label>
						    <div class="layui-input-block">
						    	@if ($user->join_temp)
						    		<input type="radio" name="L_temp" value="0" title="未体验">
						      		<input type="radio" name="L_temp" value="1" title="已体验" checked="checked">
						      	@else
						      		<input type="radio" name="L_temp" value="0" title="未体验" checked="checked">
						      		<input type="radio" name="L_temp" value="1" title="已体验">
						    	@endif
						    </div>
						</div>
						<div class="layui-form-item">
							<label for="L_password" class="layui-form-label">
			                  	<span class="we-red"></span>登录密码:
			              	</label>
							<div class="layui-input-inline">
								<input type="text" value="@if ($user->password){{ Crypt::decrypt($user->password) }}@endif" id="L_password" name="password" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid layui-word-aux">
								6到16个字符
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_cash_password" class="layui-form-label">
			                  	<span class="we-red"></span>支付密码:
			              	</label>
							<div class="layui-input-inline">
								<input type="text" value="@if ($user->cash_password){{ Crypt::decrypt($user->cash_password) }}@endif" id="L_cash_password" name="cash_password" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid layui-word-aux">
								6到16个字符
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_uid" class="layui-form-label">
			                  	<span class="we-red"></span>身份证号:
			              	</label>
							<div class="layui-input-inline">
								<input type="text" value="{{ $user->user_uid }}" id="L_uid" name="L_uid" autocomplete="off" class="layui-input">
							</div>
						</div>
						{{-- <div class="layui-form-item">
							<label for="L_ads" class="layui-form-label">
				                <span class="we-red"></span>用户地址:
				            </label>
							<div class="layui-input-block">
								<input type="text" id="L_ads" name="ads" autocomplete="off" class="layui-input">
							</div>
						</div> --}}
						<div class="layui-form-item">
							<a class="layui-btn" onclick="L_sub()" lay-filter="add" lay-submit="">确定修改</a>
							<input type="hidden" name="dataId" id="dataId" value="" />
						</div>
					</form>
				</div>
	  		</div>
	  	</div>
	  	<br>
	  	<div class="layui-col-md12">
	      	<div class="layui-card layui-row">
	        	<div class="layui-card-header">User 通讯地址管理</div>
	        	<div class="layui-card-body">
					<form class="layui-form layui-form-pane">
						<div class="layui-row">
							<div class="layui-row">
								<div class="layui-form-item">
									<label for="city" class="layui-form-label">
										<span class="we-red"></span>通讯地址:
									</label>
									<div class="layui-input-block">
										<input value="{{ $user->province }}/{{ $user->city }}/{{ $user->area }}" type="text" id="city" name="area" autocomplete="off" class="layui-input" placeholder="点击选择地区">
									</div>
								</div>
							</div>
						</div>
						<div class="layui-row">
							<div class="layui-row">
								<div class="layui-form-item">
									<label for="a_ads" class="layui-form-label">
										<span class="we-red"></span>详细地址:
									</label>
										<div class="layui-input-block">
											<input value="{{ $user->ads }}" type="text" id="a_ads" name="ads" autocomplete="off" class="layui-input">
									</div>
								</div>
							</div>
						</div>
						<div class="layui-form-item">
							<a class="layui-btn" onclick="adsSub()" lay-filter="add" lay-submit="">修改地址</a>
							<input type="hidden" name="dataId" value="" />
						</div>
					</form>
				</div>
	      	</div>
	    </div>
		<br>
		<div class="layui-col-md12">
			 	 <div class="layui-row layui-col-space15">
			    	<div class="layui-col-md6">
			      		<div class="layui-card">
			        		<div class="layui-card-header">绑定的上级用户</div>
			        		<div class="layui-card-body">
			          			@if ($user_pid)
			          				<a href="javascript:;" onclick="parent.tab.tabAdd('会员信息编辑','http://localhost/service/user/info/{{ $user_pid->user_uuid }}','user-info_{{ $user_pid->user_uuid }}')"><img style="display: inline-block;width: 2rem;border-radius: 50%;" src="{{ url($user_pid->user_pic) }}"> @if ($user_pid->user_name)
			          					{{ $user_pid->user_name }}
			          					@else
			          					{{ $user_pid->user_nickname }}
			          				@endif</a>
			          				<br>
			          				<p>用户账号:{{ $user_pid->user_phone }}</p>
			          				<br>
			          			@endif
			          			<a onclick="userChange()" class="layui-btn layui-btn-danger">重置绑定</a>
			        		</div>
			      		</div>
			    	</div>
			    	<div class="layui-col-md6">
			      		<div class="layui-card">
			        		<div class="layui-card-header">绑定的上级加盟商</div>
			        		<div class="layui-card-body">
			          			@if ($join_pid)
			          				<a href="javascript:;" onclick="parent.tab.tabAdd('会员信息编辑','http://localhost/service/user/info/{{ $join_pid->user_uuid }}','user-info_{{ $join_pid->user_uuid }}')"><img style="display: inline-block;width: 2rem;border-radius: 50%;" src="{{ url($join_pid->user_pic) }}"> @if ($join_pid->user_name)
			          					{{ $join_pid->user_name }}
			          					@else
			          					{{ $join_pid->user_nickname }}
			          				@endif</a>
			          				<br>
			          				<p>用户账号:{{ $join_pid->user_phone }}</p>
			          				<br>
			          			@endif
			          			<a onclick="joinChange()" class="layui-btn layui-btn-danger">重置绑定</a>
			        		</div>
			      		</div>
			    	</div>
				</div>
		</div>
	</div>
	<script type="text/javascript">
		layui.extend({
			admin: '{/}{{ url('static/js') }}/admin'
		});
		layui.use('form', function(){
			var form = layui.form;
			form.on('select(province)', function(data){
				// 获取当前身份下的市区
				console.log(data)
				getCity(data.value)
			});
		});
	</script>
<script type="text/javascript" src="{{ asset('js/Popt.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/city.json.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/citySet.js') }}"></script>
<script type="text/javascript">
// 地区加载插件
$("#city").click(function (e) {
    SelCity(this,e);
});
$("s").click(function (e) {
    SelCity(document.getElementById("city"),e);
});

// 提交添加地址的表单
function addAds() {
    var name = $('input[name=name]').val();
    var phone = $('input[name=phone]').val();
    var city = $('input[name=city]').val();
    var ads = $('input[name=ads]').val();
    $.post('{{ url('addAds') }}', {
        "_token" : '{{ csrf_token() }}',
        "name" : name,
        "phone" : phone,
        "city" : city,
        "ads" : ads
    }, function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            location.href = '{{ url('adsList') }}';
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}

// 提交基本信息
function L_sub() {
	var phone = $('#L_phone').val();
	var sex = $("input[name='L_sex']:checked").val();
	var name = $('#L_name').val();
	var nickname = $('#L_nickname').val();
	var temp = $("input[name='L_temp']:checked").val();
	var password = $('#L_password').val();
	var cash_password = $('#L_cash_password').val();
	var uid = $('#L_uid').val();
	var rank = $("#L_rank  option:selected").val();
	// console.log(phone);
	// console.log(sex);
	// console.log(name);
	// console.log(nickname);
	// console.log(temp);
	// console.log(password);
	// console.log(cash_password);
	// console.log(uid);
	// console.log(rank);

	if (!isPhone(phone)) {
		layer.msg('您输入的电话号码格式错误!', function(){
			//关闭后的操作
		});
	}else{
		loading();
		$.post('{{ url('service/user/info/edit') }}', {
			"_token" : '{{ csrf_token() }}',
			"uuid" : '{{ $user->user_uuid }}',
			"name" : name,
			"nickname" : nickname,
			"temp" : temp,
			"password" : password,
			"cash_password" : cash_password,
			"uid" : uid,
			"rank" : rank
		}, function(ret) {
			var obj = $.parseJSON(ret);
			layer.close(loadingBox);
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
	}
}

// 提交地址信息
function adsSub() {
	var area = $('input[name=area]').val();
	var ads = $('input[name=ads]').val();
	loading();
	$.post('{{ url('service/user/ads/edit') }}', {
		"_token" : '{{ csrf_token() }}',
		"uuid" : '{{ $user->user_uuid }}',
		"area" : area,
		"ads" : ads
	}, function (ret) {
		var obj = $.parseJSON(ret);
		layer.close(loadingBox);
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
	})
}

// 修改绑定的上级用户
function userChange() {
	//prompt层
	layer.prompt({title: '请输入需要绑定的用户手机号码', formType: 3}, function(val, index){
		loading();
		layer.close(index)
		if (isPhone(val)) {
			$.post('{{ url('service/user/userPid/edit') }}', {
		  		"_token" : '{{ csrf_token() }}',
		  		"uuid" : '{{ $user->user_uuid }}',
		  		"text" : val
		  	}, function (ret) {
		  		var obj = $.parseJSON(ret);
		  		layer.close(loadingBox);
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
		  	})
		}else{
			layer.msg('请输入正确的手机号码', function(){
				//关闭后的操作
			});
		}
	});
}

function joinChange() {
	//prompt层
	layer.prompt({title: '请输入需要绑定的用户手机号码', formType: 3}, function(val, index){
		loading();
		layer.close(index)
		if (isPhone(val)) {
			$.post('{{ url('service/user/joinPid/edit') }}', {
		  		"_token" : '{{ csrf_token() }}',
		  		"uuid" : '{{ $user->user_uuid }}',
		  		"text" : val
		  	}, function (ret) {
		  		var obj = $.parseJSON(ret);
		  		layer.close(loadingBox);
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
		  	})
		}else{
			layer.msg('请输入正确的手机号码', function(){
				//关闭后的操作
			});
		}
	});
}
	</script>
@endsection