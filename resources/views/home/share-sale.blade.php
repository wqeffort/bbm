@extends('lib.home.header')
@section('body')
<style type="text/css">
    .join_head {
        height: 10rem;
        background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        text-align: center;
    }
    .join_head p {
        padding: 2rem 0 1rem 0;
        font-size: 1rem;
        color: #FFF;
    }
    .join_head b {
        font-size: 1.5rem;
        color: #FFF;
    }
    .user_nav a {
        color: #FFF;
    }
    h3 {
        text-align: center;
        color: #FFF;
        padding-top: 1rem;
    }
    .join_cont {
        text-align: center;
        line-height: 4rem;
    }
    .join_cont a {
        padding: .3rem 1rem;
        background: #188aff;
        color: #FFF;
        border-radius: 1rem;
    }
</style>
<div class="join_head">
    <h3>分享我们</h3>
    <p>正在生成二维码之中,请耐心等候!</p>
    <b>长按保存或者分享</b>
</div>
<div class="join_cont">
    <img src="{{ url('images/share_load.gif') }}" id="qrcode" style="width: 12rem; height: 12rem;">
    <div style="text-align: center;">
        <input id="share" style="width: 15rem;font-size: 1rem;">
    </div>
    <p style="color:#666666;">长按文本框链接进行复制</p>
</div>
<script type="text/javascript">
$(function () {
    $.post('{{ url('user/share/qrcode') }}', {
        "_token" : '{{ csrf_token() }}'
    }, function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            $("#qrcode").attr('src',obj.data.qrcode);
            $('#share').val(obj.data.url);
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
})


</script>
@endsection






































