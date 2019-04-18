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
        background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        display: flex;
        justify-content: space-between;
        padding: 0 2rem;
    }
    .user_nav a {
        color: #FFF;
    }
    .join_cell ul {
        padding: 1rem;
    }
    .join_cell ul li {
        padding: .5rem;
        border-left: 4px solid #27c7fe;
        margin-bottom: 1rem;
        line-height: 1.5rem;
        background: #FFF;
        position: relative;
    }
    h4 {
        font-size: 1rem;
    }
    .join_cell ul li span {
        position: absolute;
        right: 2rem;
        top: 1rem;
        font-size: 1.5rem;
    }
    ul li p {
        line-height: 2rem;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>账户积分明细</h3>
    <span onclick="searchBtn()" style="font-size: 1.2rem;"></span>
</div>
<div class="join_cell">
    <ul>
        @if ($log->isNotEmpty())
            @foreach ($log as $element)
            <li>
                <h4>@switch($element->type)
                    @case(1)
                        绑定添加积分
                        @break
                    @case(2)
                        分享添加积分
                        @break
                    @case(3)
                        充值积分
                        @break
                     @case(4)
                        开通会籍
                        @break
                    @case(5)
                        销售会籍
                        @break
                    @case(6)
                        销售积分
                        @break
                    @case(7)
                        开通加盟商
                        @break
                    @case(9)
                        返佣积分转现
                        @break
                    @case(10)
                        推荐春蚕奖励
                        @break
                    @case(11)
                        累计打款50万
                        @break
                    @case(12)
                        推荐用户打款至公司
                        @break
                    @case(13)
                        积分转账-扣除
                        @break
                    @case(14)
                        积分转账-入账
                        @break
                    @case(15)
                        开通合伙人-扣除
                        @break
                    @case(16)
                        开通合伙人-入账
                        @break
                    @case(20)
                        后台扣除
                        @break
                    @case(21)
                        用户微信购买会籍
                        @break
                    @case(22)
                        用户微信支付充值
                        @break
                    @case(24)
                        开拓积分转账
                        @break
                    @case(25)
                        转换开拓积分
                    @break
                    @default
                        未知渠道
                @endswitch
                 变动明细</h4>
                <p><b style="color:green;">变动前</b> 账户 <b style="color:#C40000;">普通积分</b>: {{ $element->point }} 积分</p>
                <p><b style="color:green;">变动前</b> 账户 <b style="color:#C40000;">赠送积分</b>: {{ $element->point_give }} 积分</p>
                @if (session('join')->type == 1)
                    <p><b style="color:green;">变动前</b> 账户 <b style="color:#C40000;">开拓积分</b>: {{ $element->point_open }} 积分</p>
                @else
                    <p><b style="color:green;">变动前</b> 账户 <b style="color:#C40000;">返佣积分</b>: {{ $element->point_fund }} 积分</p>
                @endif

                <p><b style="color:#C40000;">变动后</b> 账户 <b style="color:green;">普通积分</b>: {{ $element->new_point }} 积分</p>
                <p><b style="color:#C40000;">变动后</b> 账户 <b style="color:green;">赠送积分</b>: {{ $element->new_point_give }} 积分</p>
                @if (session('join')->type == 1)
                    <p><b style="color:#C40000;">变动后</b> 账户 <b style="color:green;">开拓积分</b>: {{ $element->new_point_open }} 积分</p>
                @else
                    <p><b style="color:#C40000;">变动后</b> 账户 <b style="color:green;">返佣积分</b>: {{ $element->new_point_fund }} 积分</p>
                @endif
                <p>变动时间:{{ $element->created_at }}</p>
                @if ($element->add)
                    <span><i style="color:#C40000;" class="fa fa-angle-double-up"></i></span>
                @else
                    <span><i style="color:green;" class="fa fa-angle-double-down"></i></span>
                @endif
            </li>
            @endforeach
        @endif
    </ul>
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






































