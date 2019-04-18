@extends('lib.home.header')
@section('body')
<style type="text/css">
    .info {
        padding: 2rem;
    }
    .info img {
        width: 100%;
    }
    h3 {
        padding: .5rem;
    }
    .content {
        margin: 0 1rem;
        border-top: 1px dashed #EEE;
        padding: .5rem 0;
    }
</style>

<div class="info">
    <img src="{{ url('images/card_4.png') }}">
</div>
<div class="blank" style="background: #EEE"></div>
<div class="desc">
    <h3>体验会籍说明</h3>
    <div class="content">
        <b>侯爵会员</b>
        <p>会员优惠：获赠200,000的消费积分</p>
        <p>办理方式：在梦享家俱乐部网站或指定官方活动支付200,000RMB成为体验用户</p>
        <b>服务说明：</b>
        <p>1、仅适用于梦享家俱乐部服务平台换区商品使用；</p>
        <p>2、享受伯爵会员相应服务；</p>
    </div>

    <h3>支付方式说明</h3>
    <div class="content">
    <p>亲爱的会员，若您无法使用微信支付时，请您通过线下转账方式支付，并麻烦您<a style="color:#C40000;" href="#">与我们客服联系</a>，谢谢！</p>
    <p>汇款账号：<span class="redfont fc-ff0000 f-fs18 f-fwb">6873 6549 0192</span></p>
    <p>户名：广东梦享家生物科技有限公司</p>
    <p>开户行名称：中国银行股份有限公司广州工业园支行</p>
    </div>
    <div class="blank"></div>
    <div class="blank"></div>

    <div style="height: 5rem;"></div>
</div>
<div class="goods_nav_bottom">
    <ul>
        <li style="width: 50%; width: 50%;line-height: 3rem;padding: 0;">
            Ja Club 侯爵会籍
        </li>
        <li style="width: 50%;background: #C40000;line-height: 1.2rem;padding: 0.3rem;" onclick="member()">
            <span><b id="totalPrice">200000</b> 元  </span>
            <h4 style="font-size: 1rem;">立即购买</h4>
        </li>
    </ul>
</div>

<script type="text/javascript">
    function member() {
        // location.href = '{{ url('buyMember') }}/200000';
        layer.open({
            content: '请联系客服进行购买!'
            ,btn: '我知道了'
        });
    }
</script>
@endsection






































