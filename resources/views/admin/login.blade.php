@extends('lib.admin.header')
@section('body')
<style type="text/css">
	body {
		background: #EEE !important;
	}
	.qrcodeBox {
		text-align: center;
    	padding: 5rem 0;
	}
	.qrcodeBox img {
		width: 15rem;
	}
	.login_foot {
		position: absolute;
	    bottom: 2rem;
	    text-align: center;
	    width: 100%;
	}
</style>
	<div class="login_box">
		<h1><img src="{{ url('images/logo-text.png') }}"></h1>
		<h2>Welcome Login Ja Club Server</h2>
		<div class="qrcodeBox">
			<img src="{{ url($qrcode) }}" />
			<div style="text-align: center; margin: 3rem">
				<span id="loginMsg" style="color: #FFF;padding: .5rem 1rem;border-radius: 1rem;background: #00a5ff;">请扫描二维码进行登录</span>
			</div>
		</div>
		<div class="login_foot">
			<div class="form">
				<p style="color: #777;">Scan Qrcode</p>
				<p>&copy; 2018 Powered by <a href="javascript:;" target="_blank" style="color: #C40000">Leo BBM</a></p>
			</div>
		</div>
	</div>
<script type="text/javascript">
	// 轮询登录结果
	setInterval(function () {
		$.post('{{ url('admin/loginAuth') }}/{{ $key }}', {
			'_token' : '{{ csrf_token() }}'
		}, function (ret) {
			var obj = $.parseJSON(ret);
			if (obj.status == 'success') {
				$('#loginMsg').css({"color": "#FFF","padding": ".5rem 1rem","border-radius": "1rem","background": "#FF0085"});
				$('#loginMsg').text('认证成功，正在为您跳转！');
				setTimeout(function () {
						location.href = '{{ url('admin') }}';
					},1500);
			}else{
				console.log(obj.msg);
			}
		})
	},2000);
</script>
@endsection