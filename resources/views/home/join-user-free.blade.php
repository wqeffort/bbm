@extends('lib.home.header')
@section('body')
<style type="text/css">
    .join_head {
        height: 3rem;
        line-height: 3rem;
        text-align: center;
        color: #FFF;
        background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        display: flex;
        justify-content: space-between;
        padding: 0 1rem;
    }
    .join_head h3 {
        width: 50%;
        overflow: hidden;
    }
    .join_head h3 a {
        color: #FFF;
    }
    .join_head a i {
        color: #FFF;
        font-size: 1.5rem;
    }
    .join_head h3 img {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: .5rem;
    }
    .join_head p {
        padding: 2rem 0 1rem 0;
        font-size: 1rem;
        color: #FFF;
    }
    .join_head b {
        display: flex;
        justify-content: space-between;
        /*font-size: 1.5rem;*/
        color: #FFF;
    }
    .user_nav a {
        color: #FFF;
    }
    .join_head b {
        display: flex;
        justify-content: space-between;
        /*font-size: 1.5rem;*/
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
        overflow: hidden;
    }
    .join_cell ul li dl dd img {
        width: 2rem;
        border-radius: 50%;
        margin-top: .5rem;
    }
</style>
<div class="join_head">
    <a href="javascript:;" onclick="javascript:window.history.back(-1);"><i class="fa fa-angle-left"></i></a>
    <h3>非会员用户</h3>
    <span onclick="searchBtn()" style="font-size: 1.2rem;"></span>
    <b>共 {{ $user->count() }} 人</b>
</div>
<div class="join_cell">
    <ul>
        @if ($user->isNotEmpty())
            @foreach ($user as $element)
            <li>
                <dl>
                    <dd style="width: 10%;"><img src="{{ $element->user_pic }}"></dd>
                    <dd style="width: 30%;"><span>
                        @if ($element->user_name || $element->user_nickname)
                            @if ($element->user_name)
                                {{ $element->user_name }}
                            @else
                                {{ $element->user_nickname }}
                            @endif
                        @else
                            空
                        @endif
                    </span></dd>
                    <dd style="width: 30%;overflow: hidden;">{{ $element->created_at }}</dd>
                    <dd style="width: 30%;">{{ $element->user_phone }}</dd>
                </dl>
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






































