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
        color: #C30015;
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
    .obj {
        position: relative;
    }
    .obj_list img {
        width: 100%;
    }
    .obj_title {
        position: absolute;
        bottom: 0;
        background: rgba(0,0,0,.7);
        width: 100%;
        color: #FFF;
    }
    .obj_title h3,ul {
        padding: .5rem 1rem;
    }
    .obj_title ul {
        display: flex;
        justify-content: space-between;
    }
    .layui-m-layercont p img {
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
<div class="obj_list">
@if ($obj->isNotEmpty())
    @foreach ($obj as $element)
    <a href="javascript:;" onclick="openView({{ $element->id }})">
        <div class="obj">
            <img src="{{ url($element->img) }}">
            <div class="obj_title">
                <h3>{{ $element->title }}</h3>
                <ul>
                    <li>开始时间: {{ $element->start }}</li>
                    <li>@if ($element->start < date('Y-m-d H:i:s'))
                        <i class="fa fa-unlock-alt"></i> 已经过期
                        @else
                        <i class="fa fa-circle-o-notch fa-spin"></i> 正在进行
                    @endif
                    </li>
                </ul>
            </div>
        </div>
    </a>
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
    <a class="nav-bottom-item true" ng-repeat="i in pages" href="{{ url('rtsh/object') }}">
        <i class="fa fa-line-chart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">投 资</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('rtsh/order') }}">
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
function openView(id) {
    var box = '';
    load();
    // 发送ajax请求详情
    $.post('{{ url('rtsh/object/getDesc') }}', {
        "_token" : '{{ csrf_token() }}',
        "id" : id
    }, function(ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            box = layer.open({
                type: 1
                ,content: '<span onclick="closeBox()" class="layer_close_btn">X</span>'+obj.data
                ,anim: 'scale'
                ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
            });
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    });
}

function closeBox() {
    layer.closeAll();
}
</script>
@endsection






































