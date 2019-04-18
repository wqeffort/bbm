@extends('lib.admin.header')
@section('body')
<!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">用户管理</a> &raquo; 用户列表
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr><p>添加的加盟商,必须完成实名认证!</p></tr>
                    <tr>
                        <th>点击查询</th>
                        <td>
                            <a href="JavaScript:;"><span class="spanBtn" onclick="getUser()">点击查询</span></a>
                            <span><i class="fa fa-exclamation-circle yellow"></i>点击查询需要添加的加盟商</span>
                        </td>
                    </tr>

                    <tr id="goodsInfo" style="display: none;">
                        <th>基本信息</th>
                        <td>
                            <img id="goodsPic" style="width: 20rem;height: 10rem;" src="">
                            <h3>用户姓名:<b id="goodsName"></b></h3>
                            <p>手机号码:<b id="goodsPrice"></b></p>
                            <input type="hidden" name="user_uuid">
                        </td>
                    </tr>

                    <tr>
                        <th>推荐加盟商</th>
                        <td>
                            <a href="JavaScript:;"><span class="spanBtn" onclick="getJoin()">点击查询</span></a>
                            <span><i class="fa fa-exclamation-circle yellow"></i>点击查询推荐人,该推荐人必须为加盟商</span>
                        </td>
                    </tr>
                    
                    <tr id="goodsInfo" class="goodsInfo1" style="display: none;">
                        <th>基本信息</th>
                        <td>
                            <img id="goodsPic" class=".goodsPic" style="width: 20rem;height: 10rem;" src="">
                            <h3>用户姓名:<b id="goodsName" class="goodsName1"></b></h3>
                            <p>手机号码:<b id="goodsPrice" class="goodsPrice1"></b></p>
                            <input type="hidden" name="join_uuid">
                        </td>
                    </tr>

                    <tr>
                        <th>日志</th>
                        <td>
                            <textarea id="log"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <a href="javascript:;" onclick="sub()" class="submit">提交</a>
                            <input type="button" class="back" onclick="history.go(-1)" value="返回">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
<script type="text/javascript">
var goodsId = '';
function getUser() {
    // 弹出层,输入商品名称进行查询
    //prompt层
    layer.prompt({title: '请输入手机号码进行查询', formType: 0}, function(text, index){
        layer.close(index);
        // 发送Ajax进行查询商品
        $.post('{{ url('admin/select/getUser') }}', {
            "_token" : '{{ csrf_token() }}',
            "phone" : text
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                // 进行赋值操作
                console.log(obj.data);
                $('input[name=user_uuid]').val(obj.data.user_uuid);
                $('#goodsName').text(obj.data.user_name);
                $('#goodsPrice').text(obj.data.user_phone);
                $('#goodsPic').attr('src', 'http://jaclub.shareshenghuo.com/'+obj.data.user_uid_a);
                $('#goodsInfo').css("display","table-row")
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        })
    });
}

function getJoin() {
    // 弹出层,输入商品名称进行查询
    //prompt层
    layer.prompt({title: '请输入手机号码进行查询', formType: 0}, function(text, index){
        layer.close(index);
        // 发送Ajax进行查询商品
        $.post('{{ url('admin/select/getJoin') }}', {
            "_token" : '{{ csrf_token() }}',
            "phone" : text
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                // 进行赋值操作
                console.log(obj.data);
                $('input[name=join_uuid]').val(obj.data.user_uuid);
                $('.goodsName1').text(obj.data.user_name);
                $('.goodsPrice1').text(obj.data.user_phone);
                $('.goodsPic1').attr('src', 'http://jaclub.shareshenghuo.com/'+obj.data.user_uid_a);
                $('.goodsInfo1').css("display","table-row")
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        })
    });
}

function sub() {
    var uuid = $("input[name=user_uuid]").val();
    var join = $("input[name=join_uuid]").val();
    var log = $('#log').val();
    if (uuid == '') {
        layer.msg('请先点击查询获取用户信息',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/join/add') }}',{
            "_token" : '{{ csrf_token() }}',
            "uuid" : uuid,
            "join" : join,
            "log" : log
        },function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                history.back(-1);
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        });
    }
}
</script>
@endsection










































