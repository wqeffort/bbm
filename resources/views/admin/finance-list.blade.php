@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">财务管理</a> &raquo; 加盟商订单列表
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
                    <a href="{{ url('admin/finance/list') }}"><i class="fa fa-plus"></i>审批列表</a>
                    <a href="{{ url('admin/finance/cash') }}"><i class="fa fa-plus"></i>提现列表</a>
                    {{-- <a href="{{ url('admin/join/recharge') }}"><i class="fa fa-plus"></i>加盟商充值</a> --}}
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
                        <th class="tc">证件</th>
                        <th class="tc">电话</th>
                        <th class="tc">汇款金额</th>
                        <th class="tc">创建时间</th>
                        <th class="tc">提交人</th>
                        <th class="tc">状态</th>
                        <th class="tc">类型</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($data)
                        @foreach ($data as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">
                                @if ($value->user_pic)
                                    <img class="user_pic" src="{{ asset($value->user_pic) }}">
                                @else
                                <img class="user_pic" src="">
                                @endif
                            <td class="tc">{{ $value->user_name }}</td>
                            <td class="tc">
                                @if ($value->user_uid_a)
                                    <img style=" width: 5rem;height: 3rem;" src="{{ url($value->user_uid_a) }}">
                                @else
                                    <img style=" width: 5rem;height: 3rem;" src="">
                                @endif
                                
                            </td>
                            <td class="tc">{{ $value->user_phone }}</td>
                            <td class="tc">{{ $value->price }}</td>
                            <td class="tc">{{ $value->created_at }}</td>
                            <td class="tc">{{ $value->admin }}</td>
                            <td class="tc">
                                @if ($value->status == 1)
                                    <a href="javascript:;" onclick="status({{ $value->id }})">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" onclick="status({{ $value->id }})" >
                                        <i style="color:#C40000" class="fa fa-close"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(1)
                                        充值
                                        @break
                                    @case(2)
                                        加盟
                                        @break
                                    @default
                                        未知
                                @endswitch
                            </td>
                            <td class="tc">
                                <a href="{{ url('admin/finance/show') }}/{{ $value->id }}"><i class="fa fa-file-text"></i> 处理</a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
<script type="text/javascript">
function handle(id) {
    loading();
    $.post('{{ url('admin/finance/find') }}', {
        "_token" : '{{ csrf_token() }}',
        "id" : id
    }, function(ret) {
        var obj = $.parseJSON(ret);
        layer.close(loadingBox);
        if (obj.status == 'success') {
            layer.open({
                type: 1,
                title: '订单信息',
                // skin: 'layui-layer-rim', //加上边框
                area: ['17rem', '50%'], //宽高
                shadeClose: true, //开启遮罩关闭
                closeBtn: 0, //不显示关闭按钮
                content: '<div style="padding:1rem;"><ul><li style="margin-bottom: 1rem;"><img style="width:15rem;" src="'+obj.data.user_uid_a+'" alt="" /></li><li style="margin-bottom: 1rem;">用户姓名: '+obj.data.user_name+'</li><li style="margin-bottom: 1rem;">联系电话: '+obj.data.user_phone+'</li><li style="margin-bottom: 1rem;">打款金额: <b style="color:#C40000;">'+obj.data.price+' 元</b></li><li><img src="" alt="" /></li><li><input type="file" style="display:none;" id="upImg"/><a href="javascript:;"><label style="    padding: .3rem 1rem;background: #C40000;color: #FFF;" for="upImg">上传回执</label></a></li></ul><p><span>确定处理</span></p></div>'
            });
        }else{
            layer.msg(obj.msg,function () {

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