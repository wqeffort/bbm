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
    .rtsh_head {
        padding: 1rem;
        text-align: center;
        color: #FFF;
        background: #C30015;
    }
    .rtsh_head p {
        line-height: 3rem;
    }
    .rtsh_cell ul li {
        background: #FFF;
        border-left: 3px solid #C30015;
        padding: .5rem 1rem;
        margin: 1rem 0;
    }
    .rtsh_body {
        /*padding: 1rem;*/
    }
    .rtsh_body ul li {
        margin: 1rem;
        background: #FFF;
        padding: 1rem;
        line-height: 1.5rem;
        box-shadow: 5px 5px 5px #dadada;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>理财账户</h3>
    <a></a>
    <a style="position: absolute; right: 1rem;" href="{{ url('cash') }}">
        <span style="color: #EEE;">兑 换</span>
    </a>
</div>
<div class="rtsh_head">
    <h2>账户余额: {{ $user->rtsh_bond + $user->rtsh_frozen }} CNY</h2>
    <p>其中包含冻结金额: {{ $user->rtsh_frozen }} CNY</p>
    <p>当前订单数: {{ $allOrder }}</p>
    <ol style="text-align: right;color: #EEE;display: flex;justify-content: space-between;">
        <a style="color: #EEE;" href="javascript:;" onclick="setProtocol({{ $user->rtsh_protocol }})">
            @if ($user->rtsh_protocol == 1)
                <i class="fa fa-toggle-on"></i> 自动续投
            @else
                <i class="fa fa-toggle-off"></i> 自动续投
            @endif
        </a>
        <a style="color: #EEE;" href="{{ url('user/rtsh/list') }}">账户流水</a>
    </ol>
</div>
<div class="rtsh_body">
    <ul>
        @foreach ($data as $value)
        <li>
            <a href="javascript:;" style="color: #000" onclick=viewLog("{{ $value->num }}")>
                <dl>
                    <dd>订单编号: <b>{{ $value->num }}</b></dd>
                    <dd>项目名称: {{ $value->title }}</dd>
                    <dd>开始时间: {{ $value->start }}</dd>
                    <dd>买入金额: {{ $value->price }} 元</dd>
                    <dd>买入期限: 
                        @switch($value->time)
                            @case(1)
                                三个月
                                @break
                            @case(2)
                                六个月
                                @break
                        @endswitch
                        <span style="color:#C30015;">(年化率{{ $value->odds * 100 }}%)</span>
                    </dd>
                    {{-- <dd>买入时间: {{ $value->created_at }}</dd> --}}
                    <dd>订单状态: 
                        @switch($value->end)
                            @case(0)
                                <b style="color: green">订单未结束</b>
                                @break
                            @case(1)
                                <b style="color: blue">订单结束,未返本金</b>
                                @break
                            @case(2)
                                <b style="color: #C40000">订单已完结</b>
                                @break
                        @endswitch
                    </dd>
                    <dd style="text-align: center;    text-decoration: underline;color: #CCC;">查看订单流水</dd>
                </dl>
            </a>
        </li>
        @endforeach
    </ul>
</div>
{{-- <a href="">
    <ol style="    padding: 1rem;
    background: #C30015;
    color: #FFF;
    margin: 1rem;
    text-align: center;">查看历史订单 >></ol>
</a> --}}
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
var page = '';
function viewLog(num) {
    load();
    $.post('{{ url('rtsh/order/getLog') }}',{
        "_token" : '{{ csrf_token() }}',
        "num" : num
    },function (ret) {
        var obj = $.parseJSON(ret);
        layer.close(loadBox);
        if (obj.status == 'success') {
            var html = ''
            $.each(obj.data, function(index, val) {
                html += '<li><p>订单编号:'+val.num+'</p><p>派息金额:<b style="color:#C40000;">'+val.price+'</b></p><p>派息时间:'+val.created_at+'</p></li>'
            });
            page = layer.open({
              type: 1
              ,content: '<span class="layer_close_btn" onclick="closeLayer()">X</span><div class="rtsh_cell "><ul style="margin-top: 3rem;">'+html+'</ul></div>'
              ,anim: 'up'
              ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;background: #EEE;'
            });
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}
function closeLayer() {
    layer.close(page);
}

function setProtocol(int) {
    //询问框
    if (int == 1) {
        var text = '您确定要<b>关闭</b>自动续投吗?<b>关闭</b>后,梦享家工作人员将不能为您续投债权!'
    }else{
        var text = '您确定要<b>打开</b>自动续投吗?<b>打开</b>后,梦享家工作人员将能为您续投债权!'
    }
    layer.open({
        content: text
        ,btn: ['确定', '不要']
        ,yes: function(index){
            $.post('{{ url('rtsh/set/protocol') }}', {
                "_token" : '{{ csrf_token() }}'
            }, function(ret) {
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    location.reload();
                }else{
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            });
        }
    });
}
</script>
@endsection






































