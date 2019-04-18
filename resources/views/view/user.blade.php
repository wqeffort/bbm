@extends('lib.view.header')
@section('body')
<style type="text/css">
.user_bg ul li {
    display: flex;
    justify-content: space-between;
    padding: .5rem 1.5rem;
    width: 8rem;
    height: 1.5rem;
    line-height: 1.5rem;
    text-align: center;
}
.user_bg ul a {
    color:#FFF;
}
.user_bg ul li i {
    line-height: 1.5rem;
}

.user_body ul li img {
    width: 4rem;
    height: 4rem;
}
.user_body ul {
    display: flex;
    justify-content: space-between;
/*    border-top: .25rem solid #EEE;
    border-bottom: .25rem solid #EEE;*/
}
.user_body ul li {
    width: 33%;
    text-align: center;
    padding: 1rem 0;
}
.user_body ul li p {
    margin-top: .5rem;
    font-size: 1rem;
}

.user_body ul li a {
    color: #333;
}
.li_center {
/*    border-left: .5rem solid #EEE;
    border-right: .5rem solid #EEE;*/
}
</style>
<div class="user_bg" style="height: 12rem;border-bottom: .25rem solid #EEE;margin-bottom: .5rem;">
    @if ($user->user_pic)
        <img class="blur" src="{{ url($user->user_pic) }}">
    @else
        <img class="blur" src="">
    @endif
    <div class="user_info" style="width: auto;height: auto;left: 1rem;display: flex;top: 2rem;">
        @if ($user->user_pic)
            <img style="margin: 0;border: 5px solid #989097;" src="{{ url($user->user_pic) }}">
        @else
            <img style="margin: 0;" src="">
        @endif
        <div style="text-align: left;padding: .5rem;color: #FFF;font-size: 1.2rem;margin-left: 1rem;">
            <h4 style="color: #FFF;">{{ $user->user_nickname }}</h4>
            <h5><img style="border: 0;border-radius: 0;width: 1rem;height: 100%;margin: 0;" src="{{ asset('img/vip.png') }}">
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
            </h5>
        </div>
    </div>
    <span style="position: absolute;top: 4rem;right: 2rem;"><img style="width: 2.5rem;height: 2.5rem;" src="{{ asset('img/info_edit.png') }}"></span>
    <ul style="position: absolute;bottom: 0;background: rgba(0,0,0,.6);color: #FFF;width: 100%;display: flex;justify-content: space-between;">
        <a href="{{ url('view/point') }}">
            <li>
                <p>可用积分 {{ $user->user_point + $user->user_point_give + $user->user_point_open }}</p>
                <i class="fa fa-angle-right"></i>
            </li>
        </a>
        <a href="{{ url('view/collection') }}">
            <li>
                <p>收藏 {{ $collection }}</p>
                <i class="fa fa-angle-right"></i>
            </li>
        </a>
    </ul>
</div>


<div class="user_body">
    <ul>
        <li>
            <a href="{{ url('view/point') }}">
                <img src="{{ asset('img/user_point.png') }}">
                <p>积 分</p>
            </a>
        </li>
        <li class="li_center">
            <a href="{{ url('view/order') }}">
            <img src="{{ asset('img/user_order.png') }}">
            <p>订 单</p>
            </a>
        </li>
        <li>
            <a href="{{ url('view/card') }}">
                <img src="{{ asset('img/user_card.png') }}">
                <p>卡 包</p>
            </a>
        </li>
    </ul>
    <ul>
        <li>
            <img src="{{ asset('img/user_cash.png') }}">
            <p>兑 换</p>
        </li>
        <li class="li_center">
            <img src="{{ asset('img/user_rank.png') }}">
            <p>会 籍</p>
        </li>
        <li>
            <img src="{{ asset('img/user_help.png') }}">
            <p>帮 助</p>
        </li>
    </ul>
</div>

<div style="height: 10rem;"></div>

        
@endsection
