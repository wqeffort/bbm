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
<div class="user_nav">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <h3>购买会籍</h3>
    <p>可用积分余额</p>
    <b>{{ $user->point + $user->point_give + $user->point_fund }}</b>
</div>
<div class="join_cont">
    <p>请选择升级的会籍: <select id="rank" required="required">
        <option value="">请选择升级的会籍</option>
        <option value="1">体验用户</option>
        <option value="2">男爵会员</option>
        <option value="21">体验用户 + 男爵会员</option>
        <option value="3">子爵会员</option>
        <option value="31">体验用户 + 子爵会员</option>
        <option value="4">伯爵会员</option>
        <option value="41">体验用户 + 伯爵会员</option>
        <option value="5">侯爵会员</option>
        <option value="51">体验用户 + 侯爵会员</option>
        <option value="6">公爵会员</option>
        <option value="61">体验用户 + 公爵会员</option>
    </select></p>

    <div>请您输入手机号码: <input style="text-align: center;" type="number" maxlength="11" name="phone"></div>
    <a href="javascript:;" onclick="select()">查询核对</a>
</div>
<script type="text/javascript">
function select() {
    var rank = $('#rank option:selected').val();
    var phone = $('input[name=phone]').val();
    // 发送ajax请求数据
    if (rank == '') {
        //提示
        layer.open({
            content: '请选择升级的会籍'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if(!isPhone(phone)) {
        //提示
        layer.open({
            content: '请输入正确的手机号码'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        load();
        $.post('{{ url('join/select') }}/'+phone+'/'+rank+'',{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadBox);
            if (obj.status == 'success') {
                //页面层
                layer.open({
                    type: 1
                    ,content: '<div><ul style="display: flex;justify-content: space-between;padding: .5rem 2rem;"><li><img style="width: 4.5rem;border-radius: 50%;" src="'+obj.data.user_pic+'" alt="用户头像" /></li><li style="line-height: 1.5rem;"><h4>用户姓名: '+obj.data.user_name+'</h4><p>当前级别: '+isRank(obj.data.user_rank)+'</p><p>手机号码: '+obj.data.user_phone+'</p></li></ul><dl style="background: #ffff73;color: #C40000;padding: .5rem 2rem;"><dd>操作说明: <b>为当前用户购买会籍</b></dd><dd>扣除积分: <b>'+payRank(rank)+'</b></dd><dd style="    padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入支付密码: <input type="password" name="password"/></dd></dl><ol style="text-align: center;margin: 1rem;"><span onclick="sub()" style="padding: .5rem 1rem;background: #C40000;color: #FFF;border-radius: 1rem;">确认操作</span></ol></div>'
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
        return '1000';
    }else if (rank == '2') {
        return '10,000';
    }else if (rank == '21') {
        return '11,000';
    }else if (rank == '3') {
        return '50,000';
    }else if (rank == '31') {
        return '51,000';
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
    var rank = $('#rank option:selected').val();
    var phone = $('input[name=phone]').val();
    var password = $('input[name=password]').val();
    load();
    $.post('{{ url('join/pay') }}',{
        "_token" : '{{ csrf_token() }}',
        "phone" : phone,
        "rank" : rank,
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
                    // 成功后的回调,返回充值历史
                    location.reload();
                }
            });
        }
    });
}
</script>
@endsection






































