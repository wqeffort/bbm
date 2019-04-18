@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #efefef;
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
    .join_cell ul li {
        margin: 1rem;
        padding: .5rem;
        border-left: 3px solid #4790f5;
        background: #FFF;
        position: relative;
    }
    .join_cell ul li p {
        line-height: 2rem;
    }
    .join_cell h3 {
        color: #4790f5;
        font-weight: 400;
    }
    .join_cell span {
        position: absolute;
        top: .5rem;
        right: 1rem;
        color: #C40000;
        font-weight: bold;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>订单列表</h3>
    <span onclick="searchBtn()" style="font-size: 1.2rem;"><i class="fa fa-search"></i></span>
</div>
<div class="join_cell">
    <ul>
        @foreach ($user as $element)
            <li>
                <h3>
                    @switch($element->type)
                        @case(1)
                            会籍购买
                            @break
                        @case(2)
                            会员充值
                            @break
                        @case(3)
                            开通合伙人
                            @break
                        @case(4)
                            积分转账
                            @break
                        @default
                            未知动作
                    @endswitch
                </h3>
                <p>订单编号: <b>{{ $element->num }}</b></p>
                <p>关联用户: <b>@if ($element->user_name)
                    {{ $element->user_name }}
                @else
                    {{ $element->user_nickname }}
                @endif</b></p>
                <p>操作时间: <b>{{ $element->created_at }}</b></p>
                <span>- {{ $element->point }}</span>
            </li>
        @endforeach
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






































