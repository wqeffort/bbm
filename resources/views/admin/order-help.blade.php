@extends('lib.admin.header')
@section('body')
<style>
    .selectBtn {
        background: #C40000;
        color: #FFF !important;
        padding: .2rem .5rem;
    }
</style>
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">订单管理</a> &raquo; 代客录单 &raquo; 订单录入
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th width="120">录单说明</th>
                        <td>最多可同时购买10种商品</td>
                    </tr>
                    <tr>
                        <th width="120">商品信息</th>
                        <td>
                            <p id="goods_1">
                                <input type="text" placeholder="请输入商品关键字" name="goods_1">
                                <a href="javascript:;" class="selectBtn" onclick="selectGoods('goods_1','1')">查询商品</a>
                            </p>
                            <div id="info_1"></div>
                            <p id="goods_2">
                                <input type="text" placeholder="请输入商品关键字" name="goods_2">
                                <a href="javascript:;" class="selectBtn" onclick="selectGoods('goods_2','2')">查询商品</a>
                            </p>
                            <div id="info_2"></div>
                            <p id="goods_3">
                                <input type="text" placeholder="请输入商品关键字" name="goods_3">
                                <a href="javascript:;" class="selectBtn" onclick="selectGoods('goods_3','3')">查询商品</a>
                            </p>
                            <div id="info_3"></div>
                            <p id="goods_4">
                                <input type="text" placeholder="请输入商品关键字" name="goods_4">
                                <a href="javascript:;" class="selectBtn" onclick="selectGoods('goods_4','4')">查询商品</a>
                            </p>
                            <div id="info_4"></div>
                            <p id="goods_5">
                                <input type="text" placeholder="请输入商品关键字" name="goods_5">
                                <a href="javascript:;" class="selectBtn" onclick="selectGoods('goods_5','5')">查询商品</a>
                            </p>
                            <div id="info_5"></div>
                        </td>
                    </tr>
                    <tr>
                        <th width="120">用户信息</th>
                        <td>
                            <input type="text" placeholder="请输入用户姓名或者电话号码" name="user">
                            <a href="javascript:;" class="selectBtn" onclick="selectUser()">查询用户</a>
                            <div id="userInfo"></div>
                        </td>
                    </tr>
                    <tr>
                        <th width="120">备注信息</th>
                        <td>
                            <textarea id="log" cols="30" rows="20">请在备注信息中填写收货信息</textarea>
                        </td>
                    </tr>
                    <tr>
                        <th width="120">短信验证</th>
                        <td><input type="number" name="code" placeholder="请输入短信验证码"></td>
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
function selectGoods(name,type) {
    // console.log(type);
    // 获取输入框中的信息进行查询
    var text = $('input[name='+name+']').val();
    if (text == '') {
        layer.msg('商品关键字不能为空!', function(){
        //关闭后的操作
        });
    }else{
        $.post('{{ url('admin/order/help/select') }}', {
            "_token" : '{{ csrf_token() }}',
            "text" : text
        }, function(ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                var html = '';
                $.each(obj.data, function(index, val) {
                     html += '<li style="display: flex;justify-content: space-between;border-bottom: 1px solid #CCC;padding-bottom: .5rem;margin-bottom: 1rem;"><span>'+val.goods_name+'</span><a href="javascript:;" onclick="getAttr('+val.id+','+type+')"><i class="fa fa-check"></i></a></li>';
                });
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    shadeClose: true,
                    skin: 'yourclass',
                    area: ['420px', '240px'],
                    content: '<div style="padding:1rem;"><ul>'+html+'</ul></div>'
                });
            }else{
                layer.msg(obj.msg, function(){
                //关闭后的操作
                });
            }
        });
    }
}

function getAttr(goodsId,type) {
    $.post('{{ url('admin/order/help/select') }}/'+goodsId, {
        "_token" : '{{ csrf_token() }}'
    }, function(ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            // console.log(obj.data);
            var goods = '';
            // <td class="goods_attr_info">
            //                 {{-- <input type="radio" name="agree" value="0" checked="checked" id="n2">
            //                 <label for="n2">延后审批</label> --}}
            //                 <input type="radio" checked="checked" name="agree" value="1" id="n1">
            //                 <label for="n1">完成审批</label>
            //             </td>
            goods = '<ul><ol style="display:none;">'+obj.data.goods.id+'</ol><li>商品名称:'+obj.data.goods.goods_name+'</li><li>积分价格:'+obj.data.goods.goods_point+'<span id="add'+type+'"></span></li><li>购买数量: <input type="number" value="1" name="num"/></li><li>商品属性: <br><b id="attr_'+type+'" class="goods_attr_info" style="color:#CCC;"></b></li></ul>';
            // console.log(goods);
            $('#info_'+type).html(goods);
            // console.log(obj.data)
            // 选中属性
            var attr = '';
            $.each(obj.data.attr, function(index, val) {
                var a = '';
                var b = '';
                a += val.attr_name+"<hr>";
                $.each(val.attr, function(i, v) {
                    if (i == 0) {
                        b += '<input type="radio" value="'+v.id+'" id="attr'+type+v.id+'" name="attr_'+type+'_'+index+'" checked="checked" /><label for="attr'+type+v.id+'" onclick="addPoint('+v.attr_point+','+type+')">'+v.attr_name+'</label>'
                    }else{
                        b += '<input type="radio" value="'+v.id+'" id="attr'+type+v.id+'" name="attr_'+type+'_'+index+'" /><label for="attr'+type+v.id+'" onclick="addPoint('+v.attr_point+','+type+')">'+v.attr_name+'</label>'
                    }
                });
                attr += a + b + '<br>';
            });
            // console.log(attr);
            $('#attr_'+type).html(attr);
        }else{
            layer.msg(obj.msg, function(){
            //关闭后的操作
            });
        }
    });
}

function addPoint(point,type) {
    // console.log(point)
    // console.log(type)
    $("#add"+type).text("+"+point);
}
function selectUser() {
    var text = $('input[name=user]').val();
    $.post('{{ url('admin/order/get/user') }}', {
        "_token" : '{{ csrf_token() }}',
        "text" : text
    }, function(ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            console.log(obj.data);
            var html = '<ul><li><input type="hidden" name="uuid" value="'+obj.data.user.user_uuid+'" /></li><li>用户昵称: <img class="user_pic" src="'+obj.data.user.user_pic+'" alt="" /> '+obj.data.user.user_nickname+'</li><li>用户姓名: '+obj.data.user.user_name+'</li><li id="phone">用户电话: '+obj.data.user.user_phone+' <a href="javascript:;" class="selectBtn" onclick="sendCode('+obj.data.user.user_phone+')">发送验证码</a></li><li>基本积分: '+obj.data.user.user_point+'</li><li>赠送积分: '+obj.data.user.user_point_give+'</li></ul>';
            var ads = '<input type="radio" name="ads" id="ads" value="0" checked/><label for="ads">在备注中填写地址</label><br>';
            if (obj.data.ads) {
                $.each(obj.data.ads, function(index, val) {
                     ads += '<input type="radio" name="ads" id="ads'+index+'" value="'+val.id+'" /> <label for="ads'+index+'">'+val.province+val.city+val.area+' '+val.ads+' '+val.name+' '+val.phone+'</label><br>'
                });
            }
            $('#userInfo').html(html+ads);
        }else{
            layer.msg(obj.msg, function(){
            //关闭后的操作
            });
        }
    });
}

function sendCode(phone) {
    console.log(phone)
    if (String(phone).length == 11) {
        $.post('{{ url('admin/order/help/code') }}',{
            "_token" : '{{ csrf_token() }}',
            "phone" : phone
        },function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.msg(obj.msg);

            }else{
                layer.msg(obj.msg, function(){
                //关闭后的操作
                });
            }
        });
    }else{
        layer.msg("电话号码不符合规则!", function(){
                //关闭后的操作
        });
    }
}

function sub() {
    // 检查货物信息
    var info_1 = $('#info_1').html();
    var info_2 = $('#info_2').html();
    var info_3 = $('#info_3').html();
    var info_4 = $('#info_4').html();
    var info_5 = $('#info_5').html();
    if (info_1 != '' || info_1.length != 0) {
        var num_1 = $('#info_1 input[name=num]').val();
        var goods_1 = num_1+"^"+$('#info_1 ol').text()+"~";
        if ($('input[name=attr_1_0]:checked').val()) {
            goods_1 += $('input[name=attr_1_0]:checked').val();
        }else{
            layer.msg('该商品属性不全,请添加属性后再进行售卖!', function(){
            //关闭后的操作
            });
        }
        if ($('input[name=attr_1_1]:checked').val()) {
            goods_1 += "|" + $('input[name=attr_1_1]:checked').val();
        }
        if ($('input[name=attr_1_2]:checked').val()) {
            goods_1 += "|" + $('input[name=attr_1_2]:checked').val();
        }
        if ($('input[name=attr_1_3]:checked').val()) {
            goods_1 += "|" + $('input[name=attr_1_3]:checked').val();
        }
        if ($('input[name=attr_1_4]:checked').val()) {
            goods_1 += "|" + $('input[name=attr_1_4]:checked').val();
        }
    }
    if (info_2 != '' || info_2.length != 0) {
        var num_2 = $('#info_2 input[name=num]').val();
        var goods_2 = num_2+"^"+$('#info_2 ol').text()+"~";
        if ($('input[name=attr_2_0]:checked').val()) {
            goods_2 += $('input[name=attr_2_0]:checked').val();
        }else{
            layer.msg('该商品属性不全,请添加属性后再进行售卖!', function(){
            //关闭后的操作
            });
        }
        if ($('input[name=attr_2_1]:checked').val()) {
            goods_2 += "|" + $('input[name=attr_2_1]:checked').val();
        }
        if ($('input[name=attr_2_2]:checked').val()) {
            goods_2 += "|" + $('input[name=attr_2_2]:checked').val();
        }
        if ($('input[name=attr_2_3]:checked').val()) {
            goods_2 += "|" + $('input[name=attr_2_3]:checked').val();
        }
        if ($('input[name=attr_2_4]:checked').val()) {
            goods_2 += "|" + $('input[name=attr_2_4]:checked').val();
        }
    }
    if (info_3 != '' || info_3.length != 0) {
        var num_3 = $('#info_3 input[name=num]').val();
        var goods_3 = num_3+"^"+$('#info_3 ol').text()+"~";
        if ($('input[name=attr_3_0]:checked').val()) {
            goods_3 += $('input[name=attr_3_0]:checked').val();
        }else{
            layer.msg('该商品属性不全,请添加属性后再进行售卖!', function(){
            //关闭后的操作
            });
        }
        if ($('input[name=attr_3_1]:checked').val()) {
            goods_3 += "|" + $('input[name=attr_3_1]:checked').val();
        }
        if ($('input[name=attr_3_2]:checked').val()) {
            goods_3 += "|" + $('input[name=attr_3_2]:checked').val();
        }
        if ($('input[name=attr_3_3]:checked').val()) {
            goods_3 += "|" + $('input[name=attr_3_3]:checked').val();
        }
        if ($('input[name=attr_3_4]:checked').val()) {
            goods_3 += "|" + $('input[name=attr_3_4]:checked').val();
        }
    }
    if (info_4 != '' || info_4.length != 0) {
        var num_4 = $('#info_4 input[name=num]').val();
        var goods_4 = num_4+"^"+$('#info_4 ol').text()+"~";
        if ($('input[name=attr_4_0]:checked').val()) {
            goods_4 += $('input[name=attr_4_0]:checked').val();
        }else{
            layer.msg('该商品属性不全,请添加属性后再进行售卖!', function(){
            //关闭后的操作
            });
        }
        if ($('input[name=attr_4_1]:checked').val()) {
            goods_4 += "|" + $('input[name=attr_4_1]:checked').val();
        }
        if ($('input[name=attr_4_2]:checked').val()) {
            goods_4 += "|" + $('input[name=attr_4_2]:checked').val();
        }
        if ($('input[name=attr_4_3]:checked').val()) {
            goods_4 += "|" + $('input[name=attr_4_3]:checked').val();
        }
        if ($('input[name=attr_4_4]:checked').val()) {
            goods_4 += "|" + $('input[name=attr_4_4]:checked').val();
        }
    }
    if (info_5 != '' || info_5.length != 0) {
        var num_5 = $('#info_5 input[name=num]').val();
        var goods_5 = num_5+"^"+$('#info_5 ol').text()+"~";
        if ($('input[name=attr_5_0]:checked').val()) {
            goods_5 += $('input[name=attr_5_0]:checked').val();
        }else{
            layer.msg('该商品属性不全,请添加属性后再进行售卖!', function(){
            //关闭后的操作
            });
        }
        if ($('input[name=attr_5_1]:checked').val()) {
            goods_5 += "|" + $('input[name=attr_5_1]:checked').val();
        }
        if ($('input[name=attr_5_2]:checked').val()) {
            goods_5 += "|" + $('input[name=attr_5_2]:checked').val();
        }
        if ($('input[name=attr_5_3]:checked').val()) {
            goods_5 += "|" + $('input[name=attr_5_3]:checked').val();
        }
        if ($('input[name=attr_5_4]:checked').val()) {
            goods_5 += "|" + $('input[name=attr_5_4]:checked').val();
        }
    }
    var goods = '';
    if (goods_1) {
        goods += goods_1;
    }
    if (goods_2) {
        goods += ","+goods_2;
    }
    if (goods_3) {
        goods += ","+goods_3;
    }
    if (goods_4) {
        goods += ","+goods_4;
    }
    if (goods_5) {
        goods += ","+goods_5;
    }
    if (goods) {
        console.log(goods);
        var uuid = $('input[name=uuid]').val();
        var ads = $('input[name=ads]:checked').val();
        var log = $('#log').text();
        var code = $('input[name=code]').val();
        if (uuid) {
            if (code) {
                loading();
                $.post('{{ url('admin/order/help') }}',{
                    "_token" : '{{ csrf_token() }}',
                    "uuid" : uuid,
                    "ads" : ads,
                    "goods" : goods,
                    "log" : log,
                    "code" :code
                },function (ret) {
                    layer.close(loadingBox)
                    var obj = $.parseJSON(ret);
                    if (obj.status == 'success') {
                        layer.msg(obj.msg);
                        setTimeout(function () {
                            history.back(-1);
                        },1500)
                    }else{
                        layer.msg(obj.msg, function(){
                        //关闭后的操作
                        });
                    }
                })
            }else{
                layer.msg('请输入短信验证码!', function(){
                //关闭后的操作
                });
            }
        }else{
            layer.msg('请先查询用户信息!', function(){
            //关闭后的操作
            });
        }
    }else{
        layer.msg('请选择购买的商品!', function(){
            //关闭后的操作
            });
    }
}
</script>
@endsection










































