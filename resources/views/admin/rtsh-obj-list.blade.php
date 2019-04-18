@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">项目管理</a> &raquo; 项目列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">选择分类:</th>
                    <td>
                        <select id="type">
                            <option value="phone">手机号码</option>
                            <option value="name">用户姓名</option>
                            <option value="num">订单编号</option>
                        </select>
                    </td>
                    <td><input class="md" type="text" name="keywords" placeholder="电话号码或姓名或订单号"></td>
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
                    <a href="{{ url('admin/rtsh/order/renew') }}"><i class="fa fa-plus"></i>新建债权项目订单</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">项目标题</th>
                        <th class="tc">项目封面</th>
                        <th class="tc">项目开始时间</th>
                        <th class="tc">年化收益</th>
                        <th class="tc">状态</th>
                        <th class="tc">项目创建时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @foreach ($obj as $element)
                    <tr>
                        <td class="tc">{{ $element->id }}</td>
                        <td class="tc">{{ $element->title }}</td>
                        <td class="tc"><img style="height: 3rem;" src="{{ url( $element->img ) }}"></td>
                        <td class="tc">{{ $element->start }}</td>
                        <td class="tc" style="color:#C40000;"><b>{{ $element->odds_1 }}</b>/<b>{{ $element->odds_2 }}</b></td>
                        <td class="tc">
                            @if ($element->end)
                                项目已结束
                            @else
                                @if ($element->status)
                                    募集中
                                @else
                                    停止募集
                                @endif
                            @endif
                        </td>
                        <td class="tc">{{ $element->created_at }}</td>
                        <td class="tc">
                            <a href="{{ url('admin/rtsh/show') }}/{{ $element->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </table>

                <div class="page_list">
                    {!! $obj->links() !!}
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

function searchInfo() {
    var type = $("#type  option:selected").val();
    var text = $("input[name=keywords]").val();
    if (text == '') {
        layer.msg('查询的内容不能为空!', function(){
            // location.reload();
        });
    }else if (type == 'num') {
        layer.msg('暂不支持订单号查询', function(){
            // location.reload();
        });
    }else{
        loading();
        $.post('{{ url('admin/search/user') }}', {
            "_token" : '{{ csrf_token() }}',
            "text" : text
        }, function(ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                console.log(obj.data);
                var html = '';
                $.each(obj.data, function(index, val) {
                    var rank = '';
                    switch(val.user_rank) {
                        case '0':
                            rank = '普通用户';
                            break;
                        case '1':
                            rank = '体验会员';
                            break;
                        case '2':
                            rank = '男爵会员';
                            break;
                        case '3':
                            rank = '子爵会员';
                            break;
                        case '4':
                            rank = '伯爵会员';
                            break;
                        case '5':
                            rank = '侯爵会员';
                            break;
                        case '6':
                            rank = '公爵会员';
                            break;
                        case '10':
                            rank = '内部员工';
                    }
                    html += '<tr><td class="tc"><img class="user_pic" src="'+val.user_pic+'"></td><td class="tc">'+val.user_nickname+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+rank+'</td><td class="tc">'+val.rtsh_bond+'</td><td class="tc">'+val.rtsh_frozen+'</td><td class="tc">'+val.updated_at+'</td><td class="tc"><a href="{{ url('admin/rtsh/user') }}/'+val.user_uuid+'" target="main"><i class="fa fa-search"></i></a></td></tr>'
                });
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    shadeClose: true,
                    area: ['700px', '450px'],
                    skin: 'layui-layer-rim',
                    content: '<table class="list_tab"><tr><th class="tc">头像</th><th class="tc">昵称</th><th class="tc">姓名</th><th class="tc">电话</th><th class="tc">用户等级</th><th class="tc">债权账户</th><th class="tc">冻结账户</th><th class="tc">上次登录</th><th class="tc">查看</th></tr>'+html+'</table>'
                });
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