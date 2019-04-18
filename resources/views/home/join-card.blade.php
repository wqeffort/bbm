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
    .card_btn {
        text-align: center;
        margin-top: 3rem;
    }
    .card_btn span {
    background: -webkit-linear-gradient(20deg,#27c7fe 0%,#5a69ff 50%,#4765f5 100%);
    background: -ms-linear-gradient(20deg,#27c7fe 0%,#5a69ff 50%,#4765f5 100%);
    background: -o-linear-gradient(20deg,#27c7fe 0%,#5a69ff 50%,#4765f5 100%);
    background: -moz-linear-gradient(20deg,#27c7fe 0%,#5a69ff 50%,#4765f5 100%);
    background: linear-gradient(20deg,#27c7fe 0%,#5a69ff 50%,#4765f5 100%);
    color: #FFF;
    padding: .5rem 3rem;
    border-radius: 1rem;
    font-size: 1rem;
    }
</style>
<div class="user_nav">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <h3>名片生成</h3>
    <img style="width: 10rem;padding: 1rem;" src="{{ asset('images/logo.png') }}">
</div>
<div class="join_cont">
    <div>
        <h4>请您输入需要生成名片的用户手机号码: </h4>
        <p><input style="text-align: center;" placeholder="会员手机号码" type="number" maxlength="11" name="phone"></p>
    </div>
</div>
    <img style="position: absolute;
    top: 10%;
    width: 80%;
    left: 10%;
    border-radius: 1rem;
    box-shadow: 0 3px 10px #000000;" id="card" src="">
    <span id="imgBtn" onclick="closeImg()" style="position: absolute;
    top: 10%;
    right: 10%;
    padding: .5rem;
    border-radius: 50%;
    background: #FFF;
    width: 1rem;
    text-align: center;
    font-size: 1rem;
    height: 1rem;
    line-height: 1rem;display: none"> X </span>
<div style="padding: 1rem;background: #fffbe1;">
    <h4 style="margin-bottom: .5rem;">使用说明:</h4>
    <ul>
        <li>
            <p>1、请输入有效会员账号；</p>
        </li>
        <li>
            <p>2、点击下方[制作名片]按钮；</p>
        </li>
        <li>
            <p>3、长按已生成的名片，保存图片；</p>
        </li>
        <li>
            <p>4、或直接分享到微信或朋友圈；</p>
        </li>
    </ul>
</div>
<div class="card_btn">
    <span onclick="sub()">制作名片</span>
</div>
<script type="text/javascript">
function sub() {
    var phone = $('input[name=phone]').val();
    load();
    $.post('{{ url('join/card/make') }}',{
        "_token" : '{{ csrf_token() }}',
        "phone" : phone
    },function (ret) {
        layer.close(loadBox);
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            $('#card').attr('src',obj.data);
            $('#card').css({'display':"block"});
            $('#imgBtn').css({'display':'block'})
        }else{
            layer.open({
                content: obj.msg,
                btn: '我知道了',
                shadeClose: false,
                yes: function(){
                    // 成功后的回调,刷新
                    location.reload();
                }
            });
        }
    });
}

function closeImg() {
    $('#card').css({'display':"none"});
    $('#imgBtn').css({'display':'none'});
}
</script>
@endsection






































