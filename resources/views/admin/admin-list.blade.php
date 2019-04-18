@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">权限管理</a> &raquo; 管理员列表
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
                    <a href="{{ url('admin/add') }}"><i class="fa fa-plus"></i>添加管理员</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">头像</th>
                        <th class="tc">姓名</th>
                        <th class="tc">所属部门</th>
                        <th class="tc">权限等级</th>
                        <th class="tc">上次登录</th>
                        <th class="tc">状态</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($admin->isNotEmpty())
                        @foreach ($admin as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc"><img class="user_pic" src="{{ asset($value->user_pic) }}">
                            <td class="tc">{{ $value->user_name }}</td>
                            <td class="tc">
                                @switch($value->cate)
                                    @case(0)
                                        产品研发
                                        @break
                                    @case(1)
                                        运营部门
                                        @break
                                    @case(2)
                                        凤天呈祥
                                        @break
                                    @case(3)
                                        梦味意求
                                        @break
                                    @case(4)
                                        金屋良缘
                                        @break
                                    @case(5)
                                        愿走高飞
                                        @break
                                    @case(6)
                                        融通四海
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">
                                @switch($value->rank)
                                    @case(1)
                                        普通权限
                                        @break
                                    @case(2)
                                        高级权限
                                        @break
                                    @case(3)
                                        财务权限
                                        @break
                                    @case(9)
                                        超级权限
                                        @break
                                    @default
                                        未知用户
                                @endswitch
                            </td>
                            <td class="tc">{{ $value->updated_at }}</td>
                            <td class="tc">
                                @if ($value->status == 1)
                                    <a href="javascript:;" onclick="status({{ $value->id }})">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" onclick="status({{ $value->id }})" style="color:#000">
                                        <i class="fa fa-close"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="tc">
                                <a href="javascript:;" onclick="delAgent({{ $value->id }})">
                                    <i class="fa fa-trash"></i> 移除
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </table>

                <div class="page_list">
                    {!! $admin->links() !!}
                </div>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
<script type="text/javascript">
function status(adminId) {
    $.post('{{ url('admin/status/') }}/'+adminId+'', {
        "_token" : '{{ csrf_token() }}'
    }, function(ret) {
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
function delAgent(adminId) {
    $.post('{{ url('admin/del') }}/'+adminId+'',{
        "_token" : '{{ csrf_token() }}',
    },function (ret) {
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