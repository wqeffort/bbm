@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #EEE;
    }
    .join_head {
        height: 12rem;
        background: -webkit-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
        background: -ms-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
        background: -o-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
        background: -moz-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
        background: linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
        text-align: center;
    }
    .join_head p {
        padding: 2rem 0 1rem 0;
        font-size: 1rem;
        color: #FFF;
    }
    .join_head b {
        font-size: 1.2rem;
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
        padding: 1rem;
        line-height: 3rem;
    }
    .join_cont a {
        color: #666;
    }
    .join_cont ul li {
        border-left: 4px solid #3da503;
        background: #FFF;
        padding: 0 1rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        line-height: 3rem;
    }
    .join_cont ul li p span {
        background: #3da503;
        padding: .2rem 1rem;
        color: #FFF;
        margin-left: 1rem;
    }
    .join_cont ul li p b {
        color: #3da503;
        font-size: 1rem;
        font-weight: bold;
    }
    ol {
        text-align: center;
    }
    ol span {
        padding: .5rem 2rem;
        background: #3da503;
        color: #FFF;
        border-radius: 1rem;
    }
    .layui-m-layercont {
        padding: 5px 5px;
        line-height: 22px;
        /* text-align: center; */
        font-size: .7rem;
    }
    .layui-m-layerchild h3 {
        padding: 0 10px;
        height: 40px;
        line-height: 40px;
        font-size: 1rem;
        font-weight: 400;
        border-radius: 5px 5px 0 0;
        text-align: center;
    }
    .layui-m-layer0 .layui-m-layerchild {
        width: 80%;
        max-width: 640px;
    }
</style>
<div class="user_nav">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <h3>春 蚕 计 划</h3>
    <ul>
        <li>
            <p>创业基余额</p>
            <b>{{ $spring->price }} CNY</b>
        </li>
    </ul>
    <p style="text-align: right;font-size: .8rem;margin-right: 1rem;color: #EEE;">有效日期: {{ $spring->spring_start }} -- {{ $spring->spring_end }}</p>
    <a href="{{ url('spring/asset/log') }}" style="position: absolute;top: 1rem;right: 1rem;color: #EEE;">账户流水</a>
</div>
<div class="join_cont">
    <ul>
        <li>
            <span>销售会籍数</span>
            <p><b>{{ $count->count() }}</b> <span>详情</span></p>
        </li>
        <li>
            <span>考核会员数</span>
            <p><b>{{ $spring->spring_count }}</b> <span>详情</span></p>
        </li>
        <li>
            <span>推荐春蚕数</span>
            <p><b>{{ $spring->spring_pid_count }}</b> <span>详情</span></p>
        </li>
        <li>
            <span>参与项目次数</span>
            <p><b>{{ $spring->is_party }}</b> <span>详情</span></p>
        </li>
    </ul>
    <ol>
        <a href="{{ url('spring/atm') }}"><span>申 请 兑 换</span></a>
    </ol>
</div>
    <p style="text-align: center;margin-top: 2rem;margin-bottom: 2rem;color: #CCC;"><a onclick="readMe()" href="javascript:;">资产账户规则</a></p>

<script type="text/javascript">
function readMe() {
    layer.open({
        title: [
          '资产账户规则',
          'background-color: #FF4351; color:#fff;'
        ]
        ,content: '<p>积分账户包括：基本积分、赠送积分及返佣积分。</p>\
        <p>收益账户包括：梦享家收益、债权收益及产权收益。</p>\
        <p>基本积分：加盟商充值的消费积分基数。（基本积分为加盟商实际支付公司的充值金额兑换成等值的消费积分基数，该积分可用于为会员充值及开通会籍。）</p>\
        <p>赠送积分：公司赠送给加盟商的消费积分。（赠送积分为公司基于加盟商充值优惠、梦享家市场活动等情况额外赠送给加盟商的消费积分，该积分仅可用于为会员开通会籍。）</p>\
        <p>返佣积分：公司返佣给加盟商的消费积分。（返佣积分为公司为加盟商所推广的会员完成开通会籍或会员充值后，提供返佣积分至加盟商。该积分可用于为会员充值及开通会籍，也可转现至梦享家收益进行兑换。）</p>\
        <p>梦享家收益：加盟商经梦享家获得的相关收益奖励。（梦享家收益可用于兑换。返佣积分若需兑换，需先转现至本账户，方可进行兑换。）</p>\
        <p>债权收益：加盟商经推广会员购买债权获得的相关提成奖励。（该收益可用于兑换。）</p>\
        <p>产权收益：加盟商经推广会员购买产权获得的相关提成奖励。（该收益可用于兑换。）</p>'
    });
}
function sub() {
    var phone = $('input[name=phone]').val();
    var point = $('input[name=point]').val();
    var password = $('input[name=password]').val();
    load();
    $.post('{{ url('join/recharge') }}',{
        "_token" : '{{ csrf_token() }}',
        "phone" : phone,
        "point" : point,
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






































