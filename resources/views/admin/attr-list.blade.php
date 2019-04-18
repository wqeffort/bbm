@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">商品属性</a> &raquo; 属性列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">选择属性:</th>
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
                    <a href="{{ url('admin/attr/add') }}"><i class="fa fa-plus"></i>添加属性</a>
                    <a href="#"><i class="fa fa-recycle"></i>我是按钮</a>
                    <a href="#"><i class="fa fa-refresh"></i>我是按钮</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">属性名称</th>
                        <th class="tc">属性价格</th>
                        <th class="tc">属性积分</th>
                        <th class="tc">属性库存</th>
                        <th class="tc">售卖数量</th>
                        <th class="tc">上级ID</th>
                        <th class="tc">所属商品</th>
                        <th class="tc">商品ID</th>
                        <th class="tc">状态</th>
                        <th class="tc">修改时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($attr->isNotEmpty())
                        @foreach ($attr as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->attr_name }}</td>
                            <td class="tc">{{ $value->attr_price }}</td>
                            <td class="tc">{{ $value->attr_point }}</td>
                            <td class="tc">{{ $value->attr_depot }}</td>
                            <td class="tc">{{ $value->attr_buy }}</td>
                            <td class="tc">{{ $value->attr_pid }}</td>
                            <td class="tc">{{ $value->goods_name }}</td>
                            <td class="tc">{{ $value->goods_id }}</td>
                            <td class="tc">
                                @if ($value->status == 1)
                                    <a href="javascript:;" onclick="status({{ $value->id }})">
                                        <i class="fa fa-check" style="color:#C40000;"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" onclick="status({{ $value->id }})">
                                        <i class="fa fa-close" style="color:#27af00"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="tc">
                                {{ $value->updated_at }}
                            </td>
                            <td class="tc">
                                <a href="{{ url('admin/attr/show') }}/{{ $value->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a href="javascript:;" onclick="del({{ $value->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </table>


            </div>
        </div>
    </form>
<script type="text/javascript">
    function del(id) {
        layer.msg('禁止删除数据列',function () {

        })
    }

    function status(id) {
        $.post('{{ url('admin/attr/status') }}/'+id, {
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