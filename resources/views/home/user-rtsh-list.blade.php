@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #EEE;
    }
    .join_head {
        height: 3rem;
        line-height: 3rem;
        text-align: center;
        color: #FFF;
        background: #C30015;
        display: flex;
        justify-content: space-between;
        padding: 0 2rem;
    }
    .user_nav a {
        color: #FFF;
    }
    .rtsh_cell {
        padding: 1rem;
    }
    .rtsh_cell ul li {
        background: #FFF;
        border-left: 3px solid #C30015;
        padding: .5rem 1rem;
        margin: 1rem 0;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>理财账户流水</h3>
    <span onclick="searchBtn()" style="font-size: 1.2rem;"><i class="fa fa-search"></i></span>
</div>
<div class="rtsh_cell">
    <ul>
        @foreach ($list as $value)
            <li>
                <p>项目名称: <b>{{ $value->title }}</b></p>
                <p>订单编号: {{ $value->num }}</p>
                <p>购买金额: {{ $value->total }}</p>
                <p>购买期限: {{ $value->time * 3}}个月</p>
                <p>年化收益: @if ($value->time == 1)
                    {{ $value->odds_1 }}
                @else
                    {{ $value->odds_2 }}
                @endif</p>
                <span>变动描述: @switch($value->type)
                    @case(1)
                        产权购买
                        @break
                    @case(2)
                        债权购买
                        @break
                    @case(3)
                        提现操作
                        @break
                    @case(4)
                        产权续期
                        @break
                    @case(5)
                        订单到期,返回本金
                        @break
                    @case(6)
                        债权账户调动
                        @break
                    @case(7)
                        债权提成账户调动
                        @break
                @endswitch
                </span>
                <p>变动金额 : <b style="color:#C40000">{{ $value->new_price - $value->price  }}</b></p>
                <p>冻结金额变动 : <b style="color:#C40000">{{ $value->new_frozen - $value->frozen  }}</b></p>
                <p>变动时间 : {{ $value->created_at  }}</b></p>
            </li>
        @endforeach
    </ul>
</div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="nav-bottom">
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('rtsh') }}">
        <i class="fa fa-home" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">首 页</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('rtsh/object') }}">
        <i class="fa fa-line-chart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">投 资</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('rtsh/order') }}">
        <i class="fa fa-handshake-o" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">交 易</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item true" ng-repeat="i in pages" href="{{ url('user') }}">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>
<script type="text/javascript">

</script>
@endsection






































