@extends('lib.home.header')
@section('body')
<style type="text/css">
    .join_head {
        height: 3rem;
        line-height: 3rem;
        text-align: center;
        color: #FFF;
        background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        display: flex;
        justify-content: space-between;
        padding: 0 1rem;
    }
    .join_head h3 {
        width: 50%;
        overflow: hidden;
    }
    .join_head h3 a {
        color: #FFF;
    }
    .join_head a i {
        color: #FFF;
        font-size: 1.5rem;
    }
    .join_head h3 img {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: .5rem;
    }
    .join_head p {
        padding: 2rem 0 1rem 0;
        font-size: 1rem;
        color: #FFF;
    }
    .join_head b {
        display: flex;
        justify-content: space-between;
        /*font-size: 1.5rem;*/
        color: #FFF;
    }
    .user_nav a {
        color: #FFF;
    }
    h3 {
        text-align: center;
        color: #FFF;
    }
    .join_cont {
        text-align: center;
        padding: 0 .5rem;
    }
    .join_cont a {
        color: #333;
    }
    .join_cont ul li {
        border-bottom: 1px solid #EEE;
    }
    .join_cont ul li ol {
        display: flex;
        justify-content: space-between;
        line-height: 2.5rem;
        height: 2.5rem;
    }
    .join_cont ul li img {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        vertical-align: middle;
        margin-top: .5rem;
    }
    .name {
        width: 50%;
        overflow: hidden;
    }
    .listData {
        font-size: .8rem;
    }
</style>
<div class="join_head">
    <a href="javascript:;" onclick="javascript:window.history.back(-1);"><i class="fa fa-angle-left"></i></a>
    <h3><img src="{{ $info->user_pic }}"> @if ($info->user_name)
        <a href="">{{ $info->user_name }}</a>
    @else
        <a href="">{{ $info->user_nickname }}</a>
    @endif</h3>
    <b>共 {{ $data->count() }} 人</b>
</div>

<div class="join_cont">
    <ul>
        @if ($data->isNotEmpty())
            @foreach ($data as $element)
                    <li>
                        <ol>
                            @if ($element->user_pic)
                                <img src="{{ url($element->user_pic) }}">
                            @else
                                <img src="http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLibbVMvILCmbf4tL791qHFB4QConhKVFsQEIMzKsbnGFmic6ib1f4wZKZI8PDtwhKLxFZvqxia2hwUYg/132">
                            @endif
                            <p class="name">@if ($element->user_name)
                                {{ $element->user_name }}
                            @else
                                {{ $element->user_nickname }}
                            @endif</p>
                            <p>@if ($element->user_phone)
                                {{ $element->user_phone }}
                            @endif</p>
                            <p>@if ($element->end)
                                已经到期
                                @else
                                未到期
                            @endif</p>
                        </ol>
                        <ol class="listData">
                            <p><a href="javascript:;">赠送总额:{{ $element->point_open }}</a></p>
                            <p><a href="javascript:;">剩余积分:{{ $element->user_point_open }}</a></p>
                            <p><a href="javascript:;">到期时间:{{ $element->time }}</a></p>
                        </ol>
                    </li>
            @endforeach
        @endif
    </ul>
</div>

<script type="text/javascript">
function select() {
    var phone = $('input[name=phone]').val();
    // 发送ajax请求数据
    if(!isPhone(phone)) {
        //提示
        layer.open({
            content: '请输入正确的手机号码'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        load();
        $.post('{{ url('sale/add/select') }}/'+phone,{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadBox);
            if (obj.status == 'success') {
                //页面层
                layer.open({
                    type: 1
                    ,content: '<div><ul style="display: flex;justify-content: space-between;padding: .5rem 2rem;"><li><img style="width: 4.5rem;border-radius: 50%;" src="'+obj.data.user_pic+'" alt="用户头像" /></li><li style="line-height: 1.5rem;"><h4>用户姓名: '+obj.data.user_name+'</h4><p>当前级别: '+isRank(obj.data.user_rank)+'</p><p>手机号码: '+obj.data.user_phone+'</p></li></ul><dl style="background: #ffff73;color: #C40000;padding: .5rem 2rem;"><dd>操作说明: <b>为当前用户开通合伙人</b></dd><dd>扣除积分: <b>100,000积分</b></dd><dd style="    padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入支付密码: <input type="password" name="password"/></dd></dl><ol style="text-align: center;margin: 1rem;"><span onclick="sub()" style="padding: .5rem 1rem;background: #C40000;color: #FFF;border-radius: 1rem;">确认操作</span></ol></div>'
                    ,anim: 'up'
                    ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 280px; padding:10px 0; border:none;'
                });
            }else{
                layer.open({
                    content: obj.msg
                    ,btn: '我知道了'
                  });
            }
        });
    }
}

function isRank(int) {
    if (int == 0) {
        return '非付费用户';
    }else if (int == 1) {
        return '体验用户';
    }else if (int == 2) {
        return '男爵会员';
    }else if (int == 3) {
        return '子爵会员';
    }else if (int == 4) {
        return '伯爵会员';
    }else if (int == 5) {
        return '侯爵会员';
    }else if (int == 6) {
        return '公爵会员';
    }
    return '未知级别';
}

function payRank(rank) {
    if (rank == '1') {
        return '2000';
    }else if (rank == '2') {
        return '10,000';
    }else if (rank == '21') {
        return '11,000';
    }else if (rank == '3') {
        return '20,000';
    }else if (rank == '31') {
        return '21,000';
    }else if (rank == '4') {
        return '100,000';
    }else if (rank == '41') {
        return '101,000';
    }else if (rank == '5') {
        return '200,000';
    }else if (rank == '51') {
        return '201,000';
    }else if (rank == '6') {
        return '1,000,000';
    }else if (rank == '61') {
        return '1,001,000';
    }
}

function sub() {
    var phone = $('input[name=phone]').val();
    var password = $('input[name=password]').val();
    load();
    $.post('{{ url('sale/add') }}',{
        "_token" : '{{ csrf_token() }}',
        "phone" : phone,
        "password" : password
    },function (ret) {
        layer.close(loadBox);
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg,
                btn: '我知道了',
                shadeClose: false,
                yes: function(){
                    // 成功后的回调,返回充值历史
                    location.reload();
                }
            });
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
</script>
@endsection






































