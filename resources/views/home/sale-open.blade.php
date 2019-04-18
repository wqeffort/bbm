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
    <h3>开拓积分转账</h3>
    <p>开拓积分余额</p>
    <b>{{ $sale->point_open }}</b>
</div>
<div class="join_cont">
    <div style="">请输入手机号码: <input style="text-align: center;" type="number" maxlength="11" name="phone" required="required"></div>
    <div style="">请输入积分数量: <input style="text-align: center;" type="number" name="point_open" required="required"></div>
    <div><p>请选择时效
        <select id="time">
            <option value="1">1 个月</option>
            {{-- <option value="2">2 个月</option>
            <option value="3">3 个月</option> --}}
        </select>
        </p>
    </div>
    <a href="javascript:;" onclick="select()">查询核对</a>
    <p style="color: #4cabff;text-decoration: underline;"><a style="background: none;padding: 0;color: #4cabff;text-decoration: underline;" href="javascript:;" onclick="changePoint()">转换开拓积分?</a></p>
</div>
<script type="text/javascript">
function select() {
    var phone = $('input[name=phone]').val();
    // 发送ajax请求数据
    var point_open = $('input[name=point_open]').val();
    if (point_open % 500 == 0 && point_open <= 3000) {
        if(!isPhone(phone)) {
            //提示
            layer.open({
                content: '请输入正确的手机号码'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            load();
            $.post('{{ url('sale/add/select/open') }}/'+phone,{
                "_token" : '{{ csrf_token() }}'
            },function (ret) {
                var obj = $.parseJSON(ret);
                layer.close(loadBox);
                if (obj.status == 'success') {
                    //页面层
                    layer.open({
                        type: 1
                        ,content: '<div><ul style="display: flex;justify-content: space-between;padding: .5rem 2rem;"><li><img style="width: 4.5rem;border-radius: 50%;" src="'+obj.data.user_pic+'" alt="用户头像" /></li><li style="line-height: 1.5rem;"><h4>用户姓名: '+obj.data.user_name+'</h4><p>当前级别: '+isRank(obj.data.user_rank)+'</p><p>手机号码: '+obj.data.user_phone+'</p></li></ul><dl style="background: #ffff73;color: #C40000;padding: .5rem 2rem;"><dd>操作说明: <b>为当前用户充值开拓积分</b></dd><dd>充值上限: <b>最多可充值3000开拓积分,必须为500的整数倍</b></dd><dd style=" padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入支付密码: <input type="password" name="password"/></dd></dl><ol style="text-align: center;margin: 1rem;"><span onclick="sub()" style="padding: .5rem 1rem;background: #C40000;color: #FFF;border-radius: 1rem;">确认操作</span></ol></div>'
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
    }else{
        layer.open({
            content: '充值的积分数量必须小于3000,且为500的整数倍'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }
}

function changePoint() {
    //页面层
    layer.open({
        type: 1
        ,content: '<div><ul style="display: flex;justify-content: space-between;padding: .5rem 2rem;"><li><dd>当前积分: <b>{{ $sale->point + $sale->point_give }}</b></dd><dd>操作说明: <b>当前账户内转换开拓积分</b></dd><dd>转换规则: <b>转换后即时到账,无法转回普通积分</b></dd><dd style=" padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入转换数值: <input type="number" name="open"/></dd><dd style=" padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入支付密码: <input type="password" name="password_open"/></dd></dl></ul><ol style="text-align: center;margin: 1rem;"><span onclick="changeOpen()" style="padding: .5rem 1rem;background: #C40000;color: #FFF;border-radius: 1rem;">确认操作</span></ol></div>'
        ,anim: 'up'
        ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 280px; padding:10px 0; border:none;'
    });
}

function changeOpen() {
    var point = $('input[name=open]').val();
    var password = $('input[name=password_open]').val();
    if (point % 500 == 0 ) {
        if (password) {
            $.post('{{ url('sale/change/open') }}', {
                "_token" : '{{ csrf_token() }}',
                "point" : point,
                "password" : password
            }, function(ret) {
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
        }else{
            layer.open({
                content: '提现密码不能为空!'
                ,btn: '我知道了'
            });
        }
    }else{
        layer.open({
            content: '充值的积分数量必须为500的整数倍'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
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
    var point_open = $('input[name=point_open]').val();
    var time = $('#time option:selected').val();
        load();
        $.post('{{ url('sale/open/recharge') }}',{
            "_token" : '{{ csrf_token() }}',
            "phone" : phone,
            "password" : password,
            "point_open" : point_open,
            "time" : time
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






































