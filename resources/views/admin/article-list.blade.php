@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">文章管理</a> &raquo; 文章列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">选择文章类别:</th>
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
                    <a href="{{ url('admin/article/add') }}"><i class="fa fa-plus"></i>添加属性</a>
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
                        <th class="tc">文章封面</th>
                        <th class="tc">文章标题</th>
                        <th class="tc">所属分类</th>
                        <th class="tc">查看次数</th>
                        <th class="tc">分享次数</th>
                        <th class="tc">状态</th>
                        <th class="tc">创建时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($article->isNotEmpty())
                        @foreach ($article as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc"><img class="user_pic" src="{{ url($value->img) }}"></td>
                            <td class="tc">{{ $value->title }}</td>
                            <td class="tc">{{ $value->name }}</td>
                            <td class="tc">{{ $value->view }}</td>
                            <td class="tc">{{ $value->share }}</td>
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
                                {{ $value->created_at }}
                            </td>
                            <td class="tc">
                                <a href="{{ url('admin/article/show') }}/{{ $value->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
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