@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">订单管理</a> &raquo; 订单列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
            <form action="" method="post">
                <table class="search_tab">
                    <tr>
                        <th width="120">订单搜索:</th>
                        <td><input class="md" type="text" name="keywords" placeholder="输入订单编号或者姓名(模糊搜索)"></td>
                        <td><a href="javascript:;" onclick="searchNum()" class="submit">提交</a></td>
                    </tr>
                </table>
            </form>
        </div>
        <!--结果页快捷搜索框 结束-->
    <!--结果页快捷搜索框 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{ url('admin/rtsh/create') }}"><i class="fa fa-plus"></i>添加债权项目</a>
                    <a href="{{ url('admin/rtsh/order/renew') }}"><i class="fa fa-plus"></i>新建债权项目订单</a>
                    <a href="{{ url('admin/rtsh/order/list/all') }}"><i class="fa fa-plus"></i>历史订单</a>
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
                        <th class="tc">购入期限</th>
                        <th class="tc">购入金额</th>
                        <th class="tc">续期金额</th>
                        <th class="tc">附加金额</th>
                        <th class="tc">所属加盟商</th>
                        <th class="tc">审核状态</th>
                        <th class="tc">订单状态</th>
                        <th class="tc">派息总额</th>
                        {{-- <th class="tc">开始时间</th> --}}
                        <th class="tc">操作</th>
                    </tr>
                    @if ($order->isNotEmpty())
                        @foreach ($order as $element)
                        <tr>
                            <td class="tc">{{ $element->id }}</td>
                            <td class="tc">{{ $element->num }}</td>
                            <td class="tc">{{ $element->title }}</td>
                            <td class="tc">{{ $element->user_name }}</td>
                            <td class="tc">{{ $element->user_phone }}</td>
                            <td class="tc">{{ $element->time }} 期</td>
                            <td class="tc">{{ $element->price }}</td>
                            <td class="tc">{{ $element->price - $element->cash }}</td>
                            <td class="tc">{{ $element->cash }}</td>
                            <td class="tc">{{ $element->join_name }}</td>
                            <td class="tc">
                                @if ($element->status == 1)
                                    <a href="javascript:;">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" style="color:#000">
                                        <i class="fa fa-close"></i>
                                    </a>
                                @endif
                            </td>
                            @switch($element->end)
                                @case(0)
                                    <td class="tc" style="color:green;">期限内订单</td>
                                    @break
                                @case(1)
                                    <td class="tc" style="color:#C40000;">未完结订单</td>
                                    @break
                                @case(2)
                                    <td class="tc" style="color:yellow;">完结的订单</td>
                                    @break
                            @endswitch

                            <td class="tc">{{ $element->count }}</td>
                            {{-- <td class="tc">{{ $element->start }}</td> --}}
                            <td class="tc">
                            <a href="{{ url('admin/rtsh/order/view/') }}/{{ $element->num }}"><i class="fa fa-search"></i>查 看 </a>
                                <a href="javascript:;" onclick=refundEnd("{{ $element->num }}")><i class="fa fa-check-square"></i>完 结</a>
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

function refundEnd(num) {
    layer.confirm('是否确定进行完结,点击确定后无法取消!', {
        btn: ['取消','确定'] //按钮
    }, function(){
        layer.msg('已经取消!');
    }, function(){
        loading();
        $.post('{{ url('admin/rtsh/order/handle') }}', {
            "_token" : '{{ csrf_token() }}',
            "num" : num
        }, function (ret) {
            layer.close(loadingBox);
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                location.reload();
            }else{
                layer.msg(obj.msg, function(){
                    location.reload();
                });
            }
        })
    });
}

// function refund(id) {
//     loading();
//     $.post('{{ url('admin/rtsh/order/refund') }}',{
//         "_token" : '{{ csrf_token() }}',
//         "id" : id
//     },function (ret) {
//         var obj = $.parseJSON(ret);
//         layer.close(loadingBox);
//         if (obj.status == 'success') {
//             layer.msg(obj.msg);
//             location.reload();
//         }else{
//             layer.msg(obj.msg, function(){
//                 location.reload();
//             });
//         }
//     });
// }

function viewInfo(id) {
    layer.msg('暂不支持查看信息,稍后补上', function(){

    });
}

function searchNum() {
    var num = $("input[name=keywords]").val();
    if (num == '') {
        layer.msg('查询的内容不能为空!', function(){
            // location.reload();
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/search/order') }}/'+num, {
            "_token" : '{{ csrf_token() }}'
        }, function(ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                console.log(obj.data);
                var html = '';
                var status = '';
                var end = '';
                var disp = 0;
                var price = 0;
                var cash = 0;
                $.each(obj.data, function(index, val) {
                    // console.log(val);
                    if (val.status == 1) {
                        status = '<a href="javascript:;"><i class="fa fa-check"></i></a>'
                    } else {
                        status = ' <a href="javascript:;" style="color:#000"><i class="fa fa-close"></i></a>'
                    }

                    switch(val.end)
                    {
                    case 0:
                        end = '<td class="tc" style="color:green;">期限内订单</td>';
                        break;
                    case 0:
                        end = '<td class="tc" style="color:green;">未完结订单</td>';
                        break;
                    case 0:
                        end = '<td class="tc" style="color:green;">完结的订单</td>';
                        break;
                    default:
                        end = '<td class="tc" style="color:green;">未知的状态</td>'
                    }
                    if (!val.price) {
                        price = 0;
                    }else{
                        price = val.price;
                    }
                    if (!val.cash) {
                        cash = 0;
                    }else{
                        cash = val.cash;
                    }
                    disp = parseInt(price) - parseInt(cash);
                    html += '<tr><td class="tc">'+val.id+'</td><td class="tc">'+val.num+'</td><td class="tc">'+val.title+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+val.time+' 期</td><td class="tc">'+price+'</td><td class="tc">'+disp+'</td><td class="tc">'+cash+'</td><td class="tc">'+val.join_name+'</td><td class="tc">'+status+'</td>'+end+'<td class="tc">'+val.count+'</td><td class="tc"><a href="{{ url('admin/rtsh/order/view/') }}/'+val.num+'"><i class="fa fa-search"></i>查 看 </a><a href="javascript:;" onclick=refundEnd("'+val.num+'")><i class="fa fa-check-square"></i>完 结</a></td></tr>'
                });
                // console.log(html);

                $('.tab_content').html('<table class="list_tab"><tr><th class="tc">ID序</th><th class="tc">订单编号</th><th class="tc">项目名称</th><th class="tc">购入用户</th><th class="tc">联系电话</th><th class="tc">购入期限</th><th class="tc">购入金额</th><th class="tc">续期金额</th><th class="tc">附加金额</th><th class="tc">所属加盟商</th><th class="tc">审核状态</th><th class="tc">订单状态</th><th class="tc">派息总额</th><th class="tc">操作</th></tr>'+html+'</table>');
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