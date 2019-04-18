@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">售票管理</a> &raquo; 售票详情
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>数据统计</h3>
        </div>
        <form action="#" method="post">
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">桌号</th>
                        <th class="tc">已经销售</th>
                        <th class="tc">剩余座位</th>
                    </tr>
                    @if ($desk->isNotEmpty())
                        @foreach ($desk as $value)
                        <tr>
                            <td class="tc">V {{ $value->desk }}</td>
                            <td class="tc">
                                @if ($value->buy == 1 && $value->desk < 6)
                                    0
                                @else
                                    {{ $value->buy }}
                                @endif
                            </td>
                            @if ($value->surplus -  $value->buy == 0)
                                <td class="tc" style="background:#C40000;color: #FFF;">{{ $value->surplus -  $value->buy}}</td>
                            @else
                                <td class="tc">{{ $value->surplus -  $value->buy}}</td>
                            @endif
                            
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </form>
    </div>
    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">订单编号</th>
                        <th class="tc">购买用户</th>
                        <th class="tc">联系电话</th>
                        <th class="tc">购买桌号</th>
                        <th class="tc">购买数量</th>
                        <th class="tc">收货信息</th>
                        <th class="tc">购买时间</th>
                    </tr>
                    @if ($data->isNotEmpty())
                        @foreach ($data as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->order_num }}</td>
                            <td class="tc">{{ $value->user_name }}</td>
                            <td class="tc">{{ $value->user_phone }}</td>
                            <td class="tc">V {{ $value->desk }}</td>
                            <td class="tc">@if ($value->desk < 6 && $value->num == 11)
                                10
                            @else
                                {{ $value->num }}
                            @endif</td>
                            <td class="tc">{{ $value->name }} {{ $value->phone }}</td>
                            <td class="tc">
                                {{ $value->created_at }}
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </table>
                <div class="page_list">
                    {!! $data->links() !!}
                </div>
            </div>
        </div>
    </form>
<script type="text/javascript">
    function del(id) {
        layer.msg('禁止删除数据列',function () {

        })
    }

    function status(id) {
        $.post('{{ url('admin/article/status') }}/'+id, {
            "_token" : '{{ csrf_token() }}',
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                location.reload();
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        })
    }
</script>
@endsection