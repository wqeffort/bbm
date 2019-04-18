@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th width="120">上级属性:</th>
                        <td>
                            <select id="attrPid">
                                <option value="{{ $data->attr_pid }}">==不进行调整==</option>
                                @if ($attr->isNotEmpty())
                                    @foreach ($attr as $element)
                                        <option value="{{ $element->id }}">{{ $element->attr_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span><i class="fa fa-exclamation-circle yellow"></i>不选择则为顶级分类</span>
                        </td>
                    </tr>
                    <tr>
                        <th>属性名称:</th>
                        <td>
                            <input type="text" value="{{ $data->attr_name }}" name="attrName">
                            <span><i class="fa fa-exclamation-circle yellow"></i>属性名称长度请勿超过6个中文字符</span>
                        </td>
                    </tr>
                    <tr>
                        <th>属性价格:</th>
                        <td>
                            <input type="number" value="{{ $data->attr_price }}" class="sm" name="attrPrice">
                            <span><i class="fa fa-exclamation-circle yellow"></i>商品实际价格等于 商品基础价格+属性价格</span>
                        </td>
                    </tr>
                    <tr>
                        <th>属性积分:</th>
                        <td>
                            <input type="number" value="{{ $data->attr_point }}" class="sm" name="attrPoint">
                            <span><i class="fa fa-exclamation-circle yellow"></i>商品实际积分等于 商品基础积分+属性积分</span>
                        </td>
                    </tr>
                    <tr>
                        <th>属性库存:</th>
                        <td>
                            <input type="number" value="{{ $data->attr_depot }}" class="sm" name="attrDepot">
                            <span><i class="fa fa-exclamation-circle yellow"></i>属性库存必须大于1</span>
                        </td>
                    </tr>
                    <tr>
                        <th>售卖数量:</th>
                        <td>
                            <input type="number" value="{{ $data->attr_buy }}" class="sm" name="attrBuy">
                            <span><i class="fa fa-exclamation-circle yellow"></i>不建议修改售卖数</span>
                        </td>
                    </tr>
                    <tr>
                        <th>关联商品</th>
                        <td>
                            <a href="JavaScript:;"><span class="spanBtn" onclick="getGoods()">点击关联商品</span></a>
                            <span><i class="fa fa-exclamation-circle yellow"></i>Look this!请点击按钮输入商品ID查询商品,进行绑定属性</span>
                        </td>
                    </tr>
                    <tr id="goodsInfo">
                        <th>商品信息</th>
                        <td>
                            <img id="goodsPic" style="width: 10rem;height: 10rem;" src="{{ url($data->goods_pic) }}">
                            <h3>商品名称:<b id="goodsName">{{ $data->goods_name }}</b></h3>
                            <p>售卖价格(基础):<b id="goodsPrice">{{ $data->attr_price }}</b></p>
                            <p>售卖积分(基础):<b id="goodsPoint">{{ $data->attr_point }}</b></p>
                        </td>
                    </tr>
                    <tr>
                        <th>工作日志：</th>
                        <td>
                            <textarea class="lg" id="log">{{ $data->log }}</textarea>
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
var goodsId = '{{ $data->goods_id }}';
function getGoods() {
    // 弹出层,输入商品名称进行查询
    //prompt层
    layer.prompt({title: '请输入商品ID进行搜索查询', formType: 0}, function(text, index){
        layer.close(index);
        // 发送Ajax进行查询商品
        $.post('{{ url('admin/attr/getGoods') }}/'+text, {
            "_token" : '{{ csrf_token() }}'
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                // 进行赋值操作
                console.log(obj.data);
                goodsId = obj.data.id;
                $('#goodsName').text(obj.data.goods_name);
                $('#goodsPrice').text(obj.data.goods_price + " 元");
                $('#goodsPoint').text(obj.data.goods_point + " 积分");
                $('#goodsPic').attr('src','/'+obj.data.goods_pic);
                $('#goodsInfo').css("display","table-row")
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        })
    });
}
function sub() {
    var attr_pid = $("#attrPid  option:selected").val();
    var attr_name = $("input[name=attrName]").val();
    var attr_price = $("input[name=attrPrice]").val();
    var attr_point = $("input[name=attrPoint]").val();
    var attr_depot = $("input[name=attrDepot]").val();
    var attr_buy = $("input[name=attrBuy]").val();
    var log = $("#log").val();
    if (attr_name == '') {
        layer.msg('属性名称不能为空',function () {
        });
    }else if (attr_price == '') {
        layer.msg('属性价格不能为空',function () {
        });
    }else if (attr_point == '') {
        layer.msg('属性积分不能为空',function () {
        });
    }else if (attr_depot == '') {
        layer.msg('属性库存不能为空',function () {
        });
    }else if (goodsId == '') {
        layer.msg('属性必须绑定商品',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/attr/edit') }}/{{ $data->id }}',{
            "_token" : '{{ csrf_token() }}',
            "attr_pid" : attr_pid,
            "attr_name" : attr_name,
            "attr_price" : attr_price,
            "attr_point" : attr_point,
            "attr_depot" : attr_depot,
            "goods_id" : goodsId,
            "attr_buy" : attr_buy,
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










































