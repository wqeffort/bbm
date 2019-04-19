@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">站内设置</a> &raquo; 广告列表
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="#"><i class="fa fa-plus"></i>我是按钮</a>
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
                        <th class="tc">缩略图</th>
                        <th class="tc">广告描述</th>
                        <th class="tc">链接地址</th>
                        <th class="tc">广告位置</th>
                        <th class="tc">状态</th>
                        <th class="tc">修改时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($ad->isNotEmpty())
                        @foreach ($ad as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc"><img src="{{ asset($value->img) }}"></td>
                            <td class="tc">{{ $value->alt }}</td>
                            <td class="tc">{{ $value->url }}</td>
                            <td class="tc">{{ $value->title }}</td>
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
                                <a href="{{ url('admin/ad/show') }}/{{ $value->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
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
        $.post('{{ url('admin/ad/status') }}/'+id, {
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