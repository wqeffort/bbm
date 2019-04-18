@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>说明</th>
                        <td>
                            <p>您当前的权限为<b>
                                @switch($admin->cate)
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
                                @switch($admin->rank)
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
                        </b>只可添加下级权限管理员</p>
                        </td>
                    </tr>
                    <tr>
                        <th>点击查询</th>
                        <td>
                            <a href="JavaScript:;"><span class="spanBtn" onclick="getUser()">点击查询</span></a>
                            <span><i class="fa fa-exclamation-circle yellow"></i>点击查询需要添加的管理员</span>
                        </td>
                    </tr>
                    <tr id="goodsInfo" style="display: none;">
                        <th>基本信息</th>
                        <td>
                            <img id="goodsPic" style="    border-radius: 50%;width: 5rem;height: 5rem;" src="">
                            <h3>用户昵称:<b id="goodsName"></b></h3>
                            <p>手机号码:<b id="goodsPrice"></b></p>
                            <input type="hidden" name="user_uuid">
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
                $('#goodsName').text(obj.data.user_nickname);
                $('#goodsPrice').text(obj.data.user_phone);
                $('#goodsPic').attr('src', obj.data.user_pic);
                $('#goodsInfo').css("display","table-row")
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        })
    });
}
function sub() {
    var uuid = $("input[name=user_uuid]").val();
    if (uuid == '') {
        layer.msg('请先点击查询获取用户信息',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/add') }}',{
            "_token" : '{{ csrf_token() }}',
            "uuid" : uuid,
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










































