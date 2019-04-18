@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>收款人:</th>
                        <td>
                            <p>{{ $data->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>联系电话:</th>
                        <td>
                            <p>{{ $data->bank_phone }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>开户银行:</th>
                        <td>
                            <p><img src="{{ $data->logo }}" alt="">{{ $data->bank_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>开户支行:</th>
                        <td>
                            <p>{{ $data->bank_location }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>打款金额:</th>
                        <td>
                            <p><b style="color:#C40000;">{{ $data->price }}</b> 元</p>
                        </td>
                    </tr>
                     <tr>
                        <th></th>
                        <td style="background: #EEE;">
                            <img src="{{ asset($data->img) }}" alt="" id="art_thumb_img" style="max-height: 175px">
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>出款账户:</th>
                        <td>
                            <select name="img" id="img">
                                <option value="对公账户-中国银行">对公账户-中国银行</option>
                                <option value="对公账户-招商银行">对公账户-招商银行</option>
                                <option value="对公账户-工商银行">对公账户-工商银行</option>
                                <option value="周光磊-工商银行">周光磊-工商银行</option>
                                <option value="黄莎莎-中国银行">黄莎莎-中国银行</option>
                                <option value="蔡兴荣-中国银行">蔡兴荣-中国银行</option>
                                <option value="朱建锋-招商银行">朱建锋-招商银行</option>
                                <option value="朱建锋-农业银行">朱建锋-农业银行</option>
                                <option value="贺亮-招商银行">贺亮-招商银行</option>
                                <option value="朱建锋-中信银行">朱建锋-中信银行</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>操作状态:</th>
                        <td class="goods_attr_info">
                            {{-- <input type="radio" name="agree" value="1" checked="checked" id="n2">
                            <label for="n2">延后提交</label> --}}
                            <input type="radio" name="agree" value="2" id="n1">
                            <label for="n1">完成提交</label>
                        </td>
                    </tr>
                    <tr>
                        <th>打款备注:</th>
                        <td>
                            <textarea class="lg" id="log"></textarea>
                            <p>用于工作记录交接,限制长度200中文字符</p>
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
function sub(id) {
    var img = $('#img option:selected').val();
    var status = '';
    var log = $("#log").val();
    status = $("input[name=agree]:checked").val();
    if (img == '') {
        layer.msg('出款账户不能为空',function () {
        });
    }else if (status != '2') {
        layer.msg('请确认完成审批!',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/finance') }}/{{ $data->id }}/cash',{
            "_token" : '{{ csrf_token() }}',
            "img" : img,
            "status" : status,
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










































