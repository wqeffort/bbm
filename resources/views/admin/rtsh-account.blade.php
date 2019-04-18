@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">融通四海</a> &raquo; 账户调度
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>查询用户</th>
                        <td>
                            <input type="number" style="width: 10rem;" name="phone" placeholder="请输入电话号码进行查询">
                            <input type="hidden" name="uuid" >
                            <a href="javascript:;" style="background: #C40000;padding: .2rem .5rem;color: #FFF;" onclick="getUser()">查询</a>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td id="info"></td>
                    </tr>
                    <tr>
                        <th>选择账户</th>
                        <td class="goods_attr_info">
                            <input type="radio" name="type" checked="checked" value="1" id="a1">
                            <label onclick="clean()" for="a1" id="opa">债权余额</label>
                            <input type="radio" name="type" value="2" id="a2">
                            <label onclick="clean()" for="a2" id="opb">债权提成</label>
                        </td>
                    </tr>
                    <tr>
                         <th>调度金额:</th>
                         <td>
                            <input style="width: 10rem;" type="number" placeholder="请填写需要调度的金额" name="price" id="price">
                            <p>调度后,冻结账户余额:<b style="color:#C40000;" id="frozen"></b>元</p>
                         </td>
                    </tr>
                    <tr>
                        <th>是否发送短信通知</th>
                        <td class="goods_attr_info">
                            <input type="radio" name="send_sms" value="1" checked="checked" id="n2">
                            <label for="n2">发送短信</label>
                            <input type="radio"  name="send_sms" value="0" id="n1">
                            <label for="n1">不发送短信</label>
                        </td>
                    </tr>
                    <tr>
                        <th>备注信息</th>
                        <td>
                            <script id="editor" name="goods_desc" type="text/plain" style="width:800px;height:500px;">
                            </script>
                            <script type="text/javascript">
                                var ue = UE.getEditor('editor');
                            </script>
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
function getUser() {
    var phone = $('input[name=phone]').val();
    if (isPhone(phone)) {
        $.post('{{ url('admin/rtsh/get/account') }}', {
            "_token" : '{{ csrf_token() }}',
            "phone" : phone
        }, function(ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                console.log(obj.data);
                var html = '<p>姓名:<b>'+obj.data.user_name+'</b></p><p>电话:<b>'+obj.data.user_phone+'</b></p><p>冻结账户余额:<b>'+obj.data.rtsh_frozen+'</b></p><img style="width: 15rem;" src="http://jaclub.shareshenghuo.com/'+obj.data.user_uid_a+'" alt="" />'
                $('#info').html(html)

                var ue = UE.getEditor('editor');//初始化对象
                $(document).ready(function(){
                    var ue = UE.getEditor('editor');
                    ue.ready(function() {//编辑器初始化完成再赋值
                        ue.setContent(obj.data.rtsh_desc);  //赋值给UEditor
                    });
                });
                var uuid = $("input[name=uuid]").val(obj.data.user_uuid);
                if (obj.data.rtsh_bond == null) {
                    $('#opa').text('债权余额 (0)');
                }else{
                    $('#opa').text('债权余额 ('+obj.data.rtsh_bond+')');
                }
                if (obj.data.join_rtsh_bond == null) {
                    $('#opb').text('债权提成 (0)');
                }else{
                    $('#opb').text('债权提成 ('+obj.data.join_rtsh_bond+')');
                }
                
                $('#frozen').text(obj.data.rtsh_frozen);
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        });
    }else{
        layer.msg('您输入的电话号码格式不正确!',function () {
        });
    }
}

 $("#price").bind("input propertychange",function(){
    if ($('#frozen').text() == '' ) {
        layer.msg('请先进行查询用户!',function () {
            $('input[name=price]').val('');
        });
    }
    var balance = parseInt($(this).val()) + parseInt($('#frozen').text());
    $('#frozen').text(balance);
 });

 function clean() {
     $('#frozen').text('0');
     $('input[name=price]').val('');
 }

function sub() {
    var desc = UE.getEditor('editor').getContent();
    console.log(desc);
    var price = $("input[name=price]").val();
    var phone = $("input[name=phone]").val();
    var sms = $("input[name=send_sms]:checked").val();
    var type = $("input[name=type]:checked").val();
    var uuid = $("input[name=uuid]").val();
    console.log(price)
    console.log(phone)
    console.log(sms)
    console.log(type)
    if (price == '') {
        layer.msg('调度金额不能为空!',function () {
        });
    }else if (phone == '') {
        layer.msg('用户电话不能为空!',function () {
        });
    }else if (uuid == '') {
        layer.msg('未获取到用户得UUID,请重新获取!',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/account/action') }}',{
            "_token" : '{{ csrf_token() }}',
            "phone" : phone,
            "price" : price,
            "sms" : sms,
            "type" : type,
            "uuid" : uuid,
            "desc" : desc
        },function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        });
    }
}
</script>
@endsection










































