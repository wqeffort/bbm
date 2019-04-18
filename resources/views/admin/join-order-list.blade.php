@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">加盟商管理</a> &raquo; 加盟商列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">选择分类:</th>
                    <td>
                        <select>
                            <option value="">全部</option>
                            <option value="">类别1</option>
                            <option value="">类别2</option>
                        </select>
                    </td>
                    <th width="70">关键字:</th>
                    <td><input type="text" name="keywords" placeholder="关键字"></td>
                    <td><input type="submit" name="sub" value="查询"></td>
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
                    <a href="{{ url('admin/join/add') }}"><i class="fa fa-plus"></i>添加加盟商</a>
                    <a href="{{ url('admin/join/recharge') }}"><i class="fa fa-plus"></i>加盟商充值</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">加盟商姓名</th>
                        <th class="tc">联系电话</th>
                        <th class="tc">涉及金额</th>
                        <th class="tc">审批类型</th>
                        <th class="tc">提交时间</th>
                        <th class="tc">当前状态</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($order->isNotEmpty())
                        @foreach ($order as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->user_name }}</td>
                            <td class="tc">{{ $value->user_phone }}</td>
                            <td class="tc">{{ $value->price }} 元</td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(1)
                                        充 值
                                        @break
                                    @case(2)
                                        加 盟
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">{{ $value->created_at }}</td>
                            <td class="tc">
                                @if ($value->agree)
                                    审批通过
                                @else
                                    等待审批
                                @endif
                            </td>
                            <td class="tc">
                                <a href="javascript:;">
                                    @if (!$value->agree)
                                    <i class="fa fa-mail-forward"></i> 撤 销
                                    @endif
                                </a>
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
</script>
@endsection