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
        background: #333;
        display: flex;
        justify-content: space-between;
        padding: 0 2rem;
    }
    .user_nav a {
        color: #FFF;
    }
    .bank_cell {
        padding: 1rem;
    }
    .bank_cell ul li {
        background: #FFF;
        border-left: 3px solid #333;
        padding: .5rem 1rem;
        margin: 1rem 0;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>银行卡列表</h3>
    <span onclick="searchBtn()" style="font-size: 1.2rem;"><i class="fa fa-search"></i></span>
</div>

<div class="bank_cell">
    <ul>
        <p>继续 <a href="{{ url('bank/create') }}">添加银行卡?</a></p>
        <p><b style="color:#C40000;">绑定银行卡之前,请先完成实名认证!</b></p>
        @if ($data->isNotEmpty())
            @foreach ($data as $value)
                <li>
                    <dl>
                        <dd style="line-height: 2rem;display: flex;justify-content: space-between;"><img src="{{ $value->bank_logo }}"><p>{{ $value->bank_name }}</p></dd>
                        <dd>
                            <p style="padding:.5rem 0;text-align:center;font-size: 1.2rem;font-weight: bold;letter-spacing: .2rem; color:#333;">{{ $value->bank_card }}</p>
                        </dd>
                        <dd style="display: flex;justify-content: space-between;">
                            <p>持卡人: {{ $value->name }}</p>
                            @if ($value->status == 1)
                                <span style="background:#333;color: #FFF;padding: 0 .5rem;">当前默认</span>
                            @else
                                <span style="background:#C40000;color: #FFF;padding: 0 .5rem;" onclick="status({{ $value->id }})">设为默认</span>
                            @endif
                        </dd>
                        <br>
                        <dd style="display: flex;justify-content: space-between;    border-top: 1px dashed #999;padding-top: .5rem;margin-top: 1rem;">
                            <span>{{ $value->bank_location }}</span>
                            <a onclick="delBank({{$value->id}})"><i class="fa fa-trash"></i> &nbsp;删除</a>
                        </dd>
                    </dl>
                </li>
            @endforeach
        @else
        <script>location.href = '{{ url('bank/create') }}'</script>
        @endif
    </ul>
</div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="nav-bottom">
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
    function status(id) {
        load();
        $.post('{{ url('bank/status') }}',{
            "_token" : '{{ csrf_token() }}',
            "id" : id
        },function (ret) {
            layer.close(loadBox);
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
                setTimeout(function () {
                    location.reload();
                }, 1500)
            }
        })
    }

    // 删除银行卡
    function delBank(id) { 
        layer.open({
            content: '您确定删除该银行卡吗?'
            ,btn: ['删除', '取消']
            ,skin: 'footer'
            ,yes: function(index){
                layer.open({content: '执行删除操作'});
                load();
                $.post('{{url('bank/del')}}/'+id,{
                    "_token" : '{{ csrf_token() }}',
                },function (ret) { 
                    layer.close(loadBox);
                    var obj = $.parseJSON(ret);
                    if (obj.status == 'success') {
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1500)
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






































