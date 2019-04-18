@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">用户管理</a> &raquo; 用户列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
        <form>
            <table class="search_tab">
                <tr>
                    <th width="120">查询选项:</th>
                    <td>
                        <select id="type">
                            <option value="phone">电话号码</option>
                            <option value="name">用户姓名</option>
                        </select>
                    </td>
                    <td><input class="md" type="text" name="keywords" placeholder="电话号码或姓名"></td>
                    <td><a href="javascript:;" onclick="searchInfo()" class="submit">提交</a></td>
                </tr>
            </table>
        </form>
    </div>
    <!--结果页快捷搜索框 结束-->
    
    <!--搜索结果页面 列表 开始-->
    <form>
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
                <table class="list_tab" id="info">
                </table>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
<script>
function searchInfo() {
    var type = $("#type  option:selected").val();
    var text = $("input[name=keywords]").val();
    if (text == '') {
        layer.msg('请输入需要查询的内容',function () {
        });
    }else{
        $.post('{{ url('admin/search') }}/'+type, {
            "_token" : '{{ csrf_token() }}',
            "text" : text
        }, function(ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                console.log(obj.data);
                var html = '';
                if (type == 'name' || type == 'phone') {
                    var tr = '<tr>\
                        <th class="tc">OPENID</th>\
                        <th class="tc">头像</th>\
                        <th class="tc">昵称</th>\
                        <th class="tc">姓名</th>\
                        <th class="tc">电话</th>\
                        <th class="tc">用户等级</th>\
                        <th class="tc">普通积分</th>\
                        <th class="tc">赠送积分</th>\
                        <th class="tc">上次登录</th>\
                        <th class="tc">查看</th></tr>';
                }
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
                            break;
                    }
                    html += '<tr><td class="tc">'+val.user_openid+'</td><td class="tc"><img class="user_pic" src="'+val.user_pic+'"></td><td class="tc">'+val.user_nickname+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+rank+'</td><td class="tc">'+val.user_point+'</td><td class="tc">'+val.user_point_give+'</td><td class="tc">'+val.updated_at+'</td><td class="tc"><a href="{{ url('admin/view/user') }}/'+val.user_uuid+'"><i class="fa fa-search"></i>查看资料</a> | <a href="{{ url('admin/view/log') }}/'+val.user_uuid+'"><i class="fa fa-file-text"></i>账户明细</a></td></tr>'
                });
                    $('#info').html(tr+html);
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        });
    }
}
$(function(){
    $(document).keydown(function(event){
        if(event.keyCode==13){
            $(".submit").click();
        }
    });
})
</script>
@endsection