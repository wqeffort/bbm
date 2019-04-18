@extends('lib.home.header')
@section('body')
<style type="text/css">
.recharge_info {
    padding: 1rem;
}

.recharge_info dl dd img {
    width: 4rem;
    border-radius: 50%;
    margin-right: 2rem;
}
.recharge_head {
    height: 2rem;
    line-height: 2rem;
    text-align: center;
    color: #C40000;
}
ul {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 1rem;
}
ul li {
    text-align: center;
    border: 1px solid #C40000;
    border-radius: 1rem;
    height: 4rem;
    line-height: 2rem;
    padding: .5rem;
    color: #C40000;
    width: 40%;
    margin-bottom: 1rem;
}
ul li p {
    font-size: 1.2rem;
    font-weight: bold;
}
</style>
{{-- <div class="recharge_head">
    购买会籍享受折扣购物,现在购买会籍 ?
</div>
<div class="blank"></div> --}}
<div class="recharge_info">
    <dl style="display: flex;justify-content: left;">
        <dd>
            <img src="{{ url($user->user_pic) }}">
        </dd>
        <dd style="line-height: 2rem;">
            <p>我的积分余额: <span>{{ $user->user_point }}</span></p>
            <p>我的会籍级别: <span>
                @switch($user->user_rank)
                    @case(0)
                        普通用户
                        @break
                    @case(1)
                        体验会员
                        @break
                    @case(2)
                        男爵会员
                        @break
                    @case(3)
                        子爵会员
                        @break
                    @case(4)
                        伯爵会员
                        @break
                    @case(5)
                        侯爵会员
                        @break
                    @case(6)
                        公爵会员
                        @break
                    @case(10)
                        内部员工
                        @break
                    @default
                        系统繁忙
                @endswitch
            </span></p>
        </dd>
    </dl>
</div>
<div class="blank"></div>
<p style="padding: 1rem 1rem 0 1rem;font-size: 1rem;">请选择充值的积分额度</p>
<ul>
    <li onclick="recharge(1000)">
        <p>1000积分</p>
        <span>售价 1000.00 CNY</span>
    </li>
    <li onclick="recharge(5000)">
        <p>5000积分</p>
        <span>售价 5000.00 CNY</span>
    </li>
    <li onclick="recharge(10000)">
        <p>10000积分</p>
        <span>售价 10000.00 CNY</span>
    </li>
    <li onclick="recharge(20000)">
        <p>20000积分</p>
        <span>售价 20000.00 CNY</span>
    </li>
</ul>
<div class="nav-bottom" style="position: fixed;">
    <a href="{{ url('shop') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">服 务</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('article') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">资 讯</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('car') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">购物车</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('user') }}" class="nav-bottom-item false" ng-repeat="i in pages">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>

<script type="text/javascript">
// 拉取微信支付
function recharge(int) {
    location.href = '{{ url('recharge') }}/'+int+'';
}
</script>
@endsection






































