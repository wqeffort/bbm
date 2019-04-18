@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">订单管理</a> &raquo; 订单列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
            <form action="" method="post">
                <table class="search_tab">
                    <tr>
                        <th width="120">派息搜索:</th>
                        <td><input class="md" type="text" name="keywords" placeholder="请输入用户姓名"></td>
                        <td><a href="javascript:;" onclick="searchInfo()" class="submit">提交</a></td>
                    </tr>
                </table>
            </form>
        </div>
    <!--结果页快捷搜索框 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{ url('admin/rtsh/create') }}"><i class="fa fa-plus"></i>添加债权项目</a>
                    <a href="{{ url('admin/rtsh/order/renew') }}"><i class="fa fa-plus"></i>续投债权项目订单</a>
                    <a href="{{ url('admin/rtsh/order/refund/all') }}"><i class="fa fa-plus"></i>历史返息列表</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content tab_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">订单编号</th>
                        <th class="tc">项目名称</th>
                        <th class="tc">购入用户</th>
                        <th class="tc">联系电话</th>
                        <th class="tc">购入金额</th>
                        <th class="tc">购入期限</th>
                        <th class="tc">年化收益</th>
                        <th class="tc">派息金额</th>
                        <th class="tc">派息状态</th>
                        <th class="tc">开始时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($order->isNotEmpty())
                        @foreach ($order as $element)
                        <tr>
                            <td class="tc">{{ $element->rentId }}</td>
                            <td class="tc">{{ $element->num }}</td>
                            <td class="tc">{{ $element->title }}</td>
                            <td class="tc">{{ $element->user_name }}</td>
                            <td class="tc">{{ $element->user_phone }}</td>
                            <td class="tc">{{ $element->price }}</td>
                            <td class="tc">{{ $element->time }} 期</td>
                            <td class="tc">{{ $element->odds }}</td>
                            <td class="tc">{{ $element->refundPrice }}</td>
                            @switch($element->refundStatus)
                                @case(0)
                                    <td class="tc" style="color:green;">未派息</td>
                                    @break
                                @case(1)
                                    <td class="tc" style="color:#C40000;">已派</td>
                                    @break
                            @endswitch
                            <td class="tc">{{ $element->start }}</td>
                            <td class="tc">
                                <a href="javascript:;" onclick="viewInfo({{ $element->id }})"><i class="fa fa-search"></i>查 看 </a>
                                <a href="javascript:;" onclick="refund({{ $element->rentId }})"><i class="fa fa-check-square"></i>派 息</a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </table>

                <div class="page_list">
                    {!! $order->links() !!}
                </div>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
<script type="text/javascript">
function status(id) {
    loading();
    $.post('{{ url('admin/join/status') }}/'+id+'', {
        "_token" : '{{ csrf_token() }}'
    }, function(ret) {
        layer.close(loadingBox);
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.msg(obj.msg);
            location.reload();
        }else{
            layer.msg(obj.msg, function(){
                // location.reload();
            });
        }
    });
}


function refund(id) {
    layer.confirm('请问您确定现在进行派息吗?', {
        btn: ['确定','取消'] //按钮
    }, function(){
        loading();
        $.post('{{ url('admin/rtsh/order/refund') }}',{
            "_token" : '{{ csrf_token() }}',
            "id" : id
        },function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                location.reload();
            }else{
                layer.msg(obj.msg, function(){
                    location.reload();
                });
            }
        });
    }, function(){
        layer.msg('已经取消!');
    });
}

function viewInfo(id) {
    layer.msg('暂不支持查看信息,稍后补上', function(){

    });
}

function searchInfo() {
    var text = $("input[name=keywords]").val();
    if (text == '') {
        layer.msg('查询的用户姓名不能为空!', function(){
            // location.reload();
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/search/refund/order/') }}/'+text, {
            "_token" : '{{ csrf_token() }}'
        }, function(ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                console.log(obj.data);
                var end = '';
                var html = '';
                var refundStatus = '';
                $.each(obj.data, function(index, val) {
                    console.log(val.refundStatus)
                    if (val.refundStatus == 0) {
                        refundStatus = '<td class="tc" style="color:green;">未派息</td>';
                    } else {
                        refundStatus = '<td class="tc" style="color:#C40000;">已派息</td>';
                    }
                    html += '<tr><td class="tc">'+val.rentId+'</td><td class="tc">'+val.num+'</td><td class="tc">'+val.title+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+val.price+'</td><td class="tc">'+val.time+' 期</td><td class="tc">'+val.odds+'</td><td class="tc">'+val.refundPrice+'</td>'+refundStatus+'<td class="tc">'+val.start+'</td></tr>'
                });
                // console.log(html);

                $('.tab_content').html('<table class="list_tab"><tr><th class="tc">ID序</th><th class="tc">订单编号</th><th class="tc">项目名称</th><th class="tc">购入用户</th><th class="tc">联系电话</th><th class="tc">购入金额</th><th class="tc">购入期限</th><th class="tc">年化收益</th><th class="tc">派息金额</th><th class="tc">派息状态</th><th class="tc">开始时间</th></tr>'+html+'</table>');
            }else{
                layer.msg(obj.msg, function(){
                    // location.reload();
                });
            }
        });
    }
}
</script>
@endsection