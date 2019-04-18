@extends('lib.home.header')
@section('body')
<div style="text-align: center;">
	<a href="javascript:;" onclick="scan()" style="font-size: 1.2rem;padding: .5rem 1rem;background: #C40000;color:#FFF;margin-top: 50%">点击开始扫码派奖</a>
</div>
<script type="text/javascript">
	wx.ready(function () {
		wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                if (result) {
                    load();
                    // 发送AJAX获取登录权限
                    $.post('{{ url('getBarcode') }}', {
                        '_token' : '{{ csrf_token() }}',
                        'key' : result,
                    }, function(ret) {
                        var obj = $.parseJSON(ret);
                        layer.close(loadBox);
                        if (obj.status == 'success') {
                            layer.open({
							    content: obj.msg
							    ,btn: '我知道了'
							});
                        }else{
                            layer.open({
                                content: obj.msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                        }
                    });
                }else{
                    layer.open({
                        content: 'Sorry,通讯失败!未获取到条码信息'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        });
    });

    function scan() {
    	wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                if (result) {
                    load();
                    // 发送AJAX获取登录权限
                    $.post('{{ url('getBarcode') }}', {
                        '_token' : '{{ csrf_token() }}',
                        'key' : result,
                    }, function(ret) {
                        var obj = $.parseJSON(ret);
                        layer.close(loadBox);
                        if (obj.status == 'success') {
                            layer.open({
							    content: obj.msg
							    ,btn: '我知道了'
							});
                        }else{
                            layer.open({
                                content: obj.msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                        }
                    });
                }else{
                    layer.open({
                        content: 'Sorry,通讯失败!未获取到条码信息'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        });
    }
</script>
@endsection