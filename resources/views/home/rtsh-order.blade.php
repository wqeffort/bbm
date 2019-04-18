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
        background: #FFF;
        display: flex;
        justify-content: space-between;
        padding: 0 2rem;
    }
    .user_nav a {
        color: #FFF;
    }
    .join_cell ul li dl {
        display: flex;
        justify-content: space-between;
        padding: 0 .5rem;
        height: 3rem;
        line-height: 3rem;
        border-bottom: 1px solid #EEE;
    }
    .join_cell ul li dl dd {
        text-align: center;
    }
    .join_cell ul li dl dd img {
        width: 2rem;
        border-radius: 50%;
        margin-top: .5rem;
    }
    .rt_title {
        margin: .5rem 0;
        border-left: 5px solid #CF0013;
        padding: .5rem;
        background: #FFF;
    }
    .rt_title ul li {
        padding: 0 .5rem;
    }
    .rt_title span {
        color: #666;
    }
    .article {
        /*margin: .5rem;*/
        background: #FFF;
        padding: .5rem;
        /*border-radius: .5rem;*/
        margin-bottom: 1rem;
    }
    .article_img {
        width: 100%;
        /*border-radius: .5rem;*/
    }
    .article ul {
        display: flex;
        justify-content: space-between;
    }
    .article ul li {
        /*padding: 0 .5rem;*/
    }
    .article ul li img {
        width: 2rem;
        border-radius: 50%;
    }
    .article h3 {
        padding: .5rem;
    }
    .article dl {
        display: flex;
        justify-content: space-between;
        line-height: 2rem;
    }
    .article dl dd {
        padding: 0 .5rem;
    }
    .article dl dd i {
        font-size: 1.2rem;
    }
    .article_list a {
        color:#333;
    }
    .nav-bottom-item.true {
        color: #C30015;
    }
    .rt_cell {
        background: #FFF;
        margin-bottom: .5rem;
        padding: .5rem;
    }
    .rt_cell ul {
        display: flex;
        justify-content: space-between;
    }
    .rt_cell ul li img {
        width: 100%;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn" style="background: #C30015;margin: .7rem;padding: .2rem .5rem;"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3><img style="height: 2rem;margin-top: .5rem;" src="{{ asset('images/rtsh_log.png') }}"></h3>
    <span style="font-size: 1.2rem;"></span>
</div>
<div class="rt_list">
    @if ($order->isNotEmpty())
        @foreach ($order as $element)
            <div class="rt_cell">
                <ul>
                    <li style="width: 30%;margin-right: 1rem;"><img src="{{ asset($element->img) }}"></li>
                    <li style="width: 70%;">
                        <h3>{{ $element->title }}</h3>
                        <p><i class="fa fa-clock-o"></i> 开始时间:<b style="color:#C40000;">{{ $element->start }}</b></p>
                        <p><i class="fa fa-arrow-circle-o-up"></i>@switch($element->time)
                            @case(1)
                                三个月收益:<b style="color:#C40000;">{{ $element->odds_1 * 100 }}%</b>
                                @break
                            @case(2)
                                六个月收益:<b style="color:#C40000;">{{ $element->odds_2 * 100 }}%</b>
                                @break
                        @endswitch
                        </p>
                    </li>
                </ul>
                <ul style="padding:.5rem .5rem 0 .5rem;border-top: 1px solid #EEE;margin-top: .5rem;">
                    <li>投资金额: {{ $element->price }} CNY</li>
                    <li>状态:@if ($element->objEnd == 0)
                        进行中
                    @else
                    已结束
                    @endif</li>
                </ul>
            </div>
        @endforeach
    @endif
</div>
<div class="blank"></div>
<div class="blank"></div>
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
    <a class="nav-bottom-item true" ng-repeat="i in pages" href="{{ url('car') }}">
        <i class="fa fa-handshake-o" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">交 易</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('user') }}">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>
<script type="text/javascript">
function searchBtn() {
    layer.open({
        content: '暂不开放搜索功能!'
        ,skin: 'msg'
        ,time: 2 //2秒后自动关闭
      });
}
</script>
@endsection






































