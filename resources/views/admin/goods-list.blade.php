@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">商品分类</a> &raquo; 分类列表
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
                    <a href="{{ url('admin/goods/add') }}"><i class="fa fa-plus"></i>添加商品</a>
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
                        <th class="tc">商品名称</th>
                        <th class="tc">商品图片</th>
                        <th class="tc">所属品牌</th>
                        <th class="tc">现金价格</th>
                        <th class="tc">积分价格</th>
                        <th class="tc">搜索关键词</th>
                        <th class="tc">新品推荐</th>
                        <th class="tc">热销推荐</th>
                        <th class="tc">状态</th>
                        <th class="tc">更新时间</th>
                        <th class="tc">库存</th>
                        <th class="tc">销量</th>
                        <th class="tc">操作</th>
                    </tr>
                        @foreach ($goods->items as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->goods_name }}</td>
                            <td class="tc">
                                <img style="width:3rem;border-radius: none;" src="{{ url($value->goods_pic) }}">
                            </td>
                            <td class="tc">
                                <img style="width:3rem;border-radius: none;"  src="{{ url($value->brand_pic) }}">
                            </td>
                            <td class="tc">{{ $value->goods_price }}</td>
                            <td class="tc">{{ $value->goods_point }}</td>
                            <td class="tc">{{ $value->goods_search }}</td>
                            <td class="tc">
                                @if ($value->is_new == 1)
                                    <a href="javascript:;" onclick="isNew({{ $value->id }})">
                                        <i class="fa fa-check" style="color:#C40000;"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" onclick="isNew({{ $value->id }})">
                                        <i class="fa fa-close" style="color:#27af00"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="tc">
                                @if ($value->is_hot == 1)
                                    <a href="javascript:;" onclick="isHot({{ $value->id }})">
                                        <i class="fa fa-check" style="color:#C40000;"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" onclick="isHot({{ $value->id }})">
                                        <i class="fa fa-close" style="color:#27af00"></i>
                                    </a>
                                @endif
                            </td>
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
                                {{ $value->depot }}
                            </td>
                            <td class="tc">
                                {{ $value->buy }}
                            </td>
                            <td class="tc">
                                <a href="{{ url('admin/goods/show') }}/{{ $value->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a href="javascript:;" onclick="del({{ $value->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        @endforeach
                </table>
               {{--  <div class="page_list">
                    <ul class="pagination">
                        @if ($goods->currentPage == 1)
                            <li class="disabled"><span>«</span></li>
                        @else
                            <li><a href="http://jaclub.qwop.cn/admin/goods/list/no?page={{$goods->currentPage - 1}}" rel="next">«</a></li>
                        @endif
                        @for ($i = 0; $i < $goods->lastPage ; $i++)
                            @if ($i == $goods->currentPage)
                                <li class="active"><span>{{ $i }}</span></li>
                            @else
                                <li><a href="http://jaclub.qwop.cn/admin/goods/list/no?page={{$i}}">{{ $i }}</a></li>
                            @endif
                        @endfor
                        @if ($goods->currentPage + 1 > $goods->lastPage)
                            <li><a href="" rel="next">»</a></li>
                        @else
                            <li><a href="http://jaclub.qwop.cn/admin/goods/list/no?page={{$goods->currentPage+1}}" rel="next">»</a></li>
                        @endif
                    </ul>

                </div> --}}

            </div>
        </div>
    </form>
<script type="text/javascript">
    function del(id) {
        layer.msg('禁止删除数据列',function () {

        })
    }

    function status(id) {
        $.post('{{ url('admin/goods/status') }}/'+id, {
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

    function isNew(id) {
        $.post('{{ url('admin/goods/isNew') }}/'+id, {
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

    function isHot(id) {
        $.post('{{ url('admin/goods/isHot') }}/'+id, {
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