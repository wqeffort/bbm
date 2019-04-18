@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>点击查询</th>
                        <td>
                            <a href="JavaScript:;"><span class="spanBtn" onclick="getUser()">点击查询</span></a>
                            <span><i class="fa fa-exclamation-circle yellow"></i>点击查询需要添加的加盟商</span>
                        </td>
                    </tr>
                    <tr id="userInfo">
                        <th>用户信息</th>
                        <td id="userBlock" style="display: none;justify-content: space-around;">
                            <img id="userPic" style="width: 3rem;border-radius: 50%;height: 3rem;" src="" alt="请选择用户">
                            <h3>用户姓名:<b id="userName"></b></h3>
                            <p>手机号码:<b id="userPhone"></b></p>
                            <p style="display: none;" id="userUuid"></p>
                        </td>
                    </tr>
                    <tr>
                        <th>用户充值金额</th>
                        <td>
                            <input id="price" type="number">
                            <p>基本积分增加 <b style="color:#C40000;" id="pointA">0</b> 积分</p>
                            <p>赠送积分增加 <b style="color:#C40000;" id="pointB">0</b> 积分</p>
                        </td>
                    </tr>
                    <tr>
                        <th>备注信息</th>
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
<style type="text/css">
    .cell ul li dl {
        display: flex;
        justify-content: space-between;
        padding: 0 .5rem;
        height: 3rem;
        line-height: 3rem;
        border-bottom: 1px solid #EEE;
    }
    .cell ul li dl dd {
        text-align: center;
    }
    .cell ul li dl dd img {
        width: 2rem;
        border-radius: 50%;
        margin-top: .5rem;
    }

</style>
<script type="text/javascript">
var goodsId = '';
function getUser() {
    // 弹出层,输入商品名称进行查询
    //prompt层
    layer.prompt({title: '请输入姓名进行查询(模糊查询)', formType: 0}, function(text, index){
        layer.close(index);
        // 发送Ajax进行查询商品
        $.post('{{ url('admin/search/name') }}', {
            "_token" : '{{ csrf_token() }}',
            "text" : text
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {

                var html = '';
                $.each(obj.data, function(index, val) {
                    html += '<li><dl><dd><img class="infoImg'+index+'" src="'+val.user_pic+'" alt="" /></dd><dd  class="infoName'+index+'">'+val.user_name+'</dd><dd  class="infoPhone'+index+'">'+val.user_phone+'</dd><dd style="display:none;" class="infoUuid'+index+'">'+val.user_uuid+'</dd><dd><a href="javascript:;" class="check-btn" onclick="info('+index+')"><i class="fa fa-check "></i></a></dd></dl></li>';
                });

                layer.open({
                    type: 1,
                    skin: 'layui-layer-demo', //样式类名
                    title: '查询结果',
                    closeBtn: 0, //不显示关闭按钮
                    anim: 2,
                    shadeClose: true, //开启遮罩关闭
                    content: '<div class="cell" style="width:20rem;height:20rem;">\
                        <ul>'+html+'</ul>\
                    </div>'
                });
            }else{
                layer.msg(obj.msg,function () {
                });
            }
            
        })
    });
}
function info(id) {
    var pic = $('.infoImg'+id).attr("src");
    var name = $('.infoName'+id).text();
    var phone = $('.infoPhone'+id).text();
    var uuid = $('.infoUuid'+id).text();
    // console.log(pic);
    // console.log(name);
    // console.log(phone);
    // console.log(uuid);
    // $('#userInfo').css({'display':'block'});
    $('#userPic').attr('src',pic);
    $('#userName').text(name);
    $('#userPhone').text(phone);
    $('#userUuid').text(uuid);
    $('#userBlock').css('display','flex');
    layer.closeAll();
}

// 监听输入框
$(function () {
    $('#price').bind('input propertychange', function () {
        $('#pointA').text($(this).val());
        $('#pointB').text($(this).val() * 0.3);
    })
})

function sub() {
    var uuid = $("#userUuid").text();
    var price = $('#price').val();
    var log = $('#log').val();
    console.log(uuid);
    console.log(price);
    console.log(log);
    if (uuid == '') {
        layer.msg('请先点击查询获取用户信息',function () {
        });
    }else if (price == '') {
        layer.msg('请输入用户的充值金额',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/join/recharge') }}',{
            "_token" : '{{ csrf_token() }}',
            "uuid" : uuid,
            "price" : price,
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










































