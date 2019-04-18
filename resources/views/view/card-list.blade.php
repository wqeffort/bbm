@extends('lib.view.header')
@section('body')
<style>
body {
    background: #EEE;
}
.wavy-line {
    width: 100%;
    height: 6px;
    background-image: -webkit-radial-gradient(circle, transparent, transparent 0px, #FFF 0px, #FFF 7px, transparent 5px, transparent);
    background-image: -moz-radial-gradient(circle, transparent, transparent 0px, #FFF 0px, #FFF 7px, transparent 5px, transparent);
    background-image: radial-gradient(circle, transparent, transparent 0px, #FFF 0px, #FFF 7px, transparent 5px, transparent);
    background-size: 13px 20px;
}
.card {
    background: #FFF;
    border-radius: .5rem;
    margin-bottom: 1rem;
}
.card_list {
    padding: 1rem;
}
.card_head {
    display: flex;
    justify-content: start;
    line-height: 5rem;
}
.card_bg {
    background: #007eff;
    color: #FFF;
    position: relative;
    border-radius: .5rem;
}
.card_bg img {
    border-radius: 50%;
    border: 2px solid #dadada;
    padding: .3rem;
    width: 2rem;
    height: 2rem;
    margin: 1rem 1rem 0 1rem;
    background: #FFF;
}
.card h3 {
    text-align: center;
    font-size: 1.2rem;
    font-weight: 500;
    letter-spacing: 5px;
}
.barcode {
    text-align: center;
    padding: 1rem 1rem .5rem 1rem;
}
</style>
<div class="card_list">
    @if ($card)
        @foreach ($card as $element)
            @switch($element->type)
                @case(1)
                <div class="card">
                    <div class="card_bg">
                        <div class="card_head">
                            <img src="{{ asset('images/sjag.png') }}" alt="">
                            <p>S.J.A.G</p>
                        </div>
                        <h3>梦享家三周年门票</h3>
                        <p style="padding: 1rem 0 1rem 0;text-align: center;color: #fff;font-size: .8rem;"><b style="color:#FFF;">桌号:v{{ $element->no }}</b><br><br><span style="opacity: .7;">有效期至:{{ $element->end_time }}</span></p>
                        <div class="wavy-line"></div>
                    </div>
                    <div class="barcode">
                        <img style="height: 3rem;width: 13rem;" src="data:image/png;base64,{{ $element->barcode }}" alt="barcode"   />
                        <p style="color: #999;font-weight: 500;letter-spacing: 2px;">{{ $element->num }}</p>
                        @if ($element->type == 1)
                            <p style="display: flex;justify-content: space-between;padding: .5rem;">
                                <span style="color: #666;">可使用次数: <b>{{ $element->times }}</b></span>
                                <span onclick="share()"><i class="fa fa-qrcode"></i> 分享给好友</span>
                            </p>
                        @endif
                    </div>
                    
                </div>

                    @break

                @default
                        Default case...
            @endswitch
            
        @endforeach
    @endif
</div>

<div style="height: 5rem"></div>
<div class="nav-bottom" style="position: fixed;height: 3rem;">
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="javascript:;" onclick="scanQrcode()">
        <i class="fa fa-exchange" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">扫描添加卡券</span>
    </a>
</div>

<script>
    function scanQrcode() {
        wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                alert(result);
                if (result) {
                    load();
                    // 发送AJAX获取登录权限
                    $.post('{{ url('view/card/add') }}', {
                        '_token' : '{{ csrf_token() }}',
                        'key' : result
                    }, function(ret) {
                        var obj = $.parseJSON(ret);
                        layer.close(loadBox);
                        if (obj.status == 'success') {
                            layer.open({
                                content: obj.msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
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
                        content: 'Sorry,通讯失败!未获取到二维码信息'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        });
    }

    function share() {
        alert('该功能于8月中旬上线!');
    }
</script>
@endsection




































