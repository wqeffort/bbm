@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">商品管理</a> &raquo; 信息统计
    </div>
    <!--面包屑导航 结束-->
    <div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">单日查询:</th>
                    <td><input type="date" name="starta" placeholder="选择日期"></td>
                    <td><a href="javascript:;" class="submit" onclick="suba()">查 询</a></td>
                </tr>
                <tr>
                    <th width="120">开始时间:</th>
                    <td><input type="date" name="start" placeholder="开始时间"></td>
                    <th width="120">结束时间:</th>
                    <td><input type="date" name="end" placeholder="结束时间"></td>
                    <td>
                        <a href="javascript:;" class="submit" onclick="sub()">查 询</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div class="result_wrap">
        <div class="result_title">
            <h3>实时订单统计</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>订单总数</label><span id="order">{{ $order->count() }} 单</span>
                    <label>销售件数</label><span id="totalNum">{{ $orderAll->sum('goods_num') }} 件</span>
                    <label>总销售额</label><span id="total">{{ $orderAll->sum('point') }} 积分/元</span>
                </li>
                <li>
                    <label>积分收入</label><span id="pointAll">{{ $pointAll }} 积分</span>
                    <label>现金收入</label><span id="priceAll">{{ $priceAll }} 元</span>
                </li>
            </ul>
        </div>
        {{-- <div class="result_title">
            <h3>实时商城统计</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>总订单数</label><span></span>
                    <label>总交易额</label><span></span>
                    <label>微信支付</label><span></span>
                    <label>积分支付</label><span></span>
                </li>
            </ul>
        </div> --}}
    </div>
    <div class="result_wrap">
        <div class="result_content">
            <table class="list_tab" id="goodsList">

            </table>
        </div>
    </div>
    <!--结果集列表组件 结束-->
    <script>
        // 查询数据
        function sub() {
            var start = $('input[name=start]').val();
            var end = $('input[name=end]').val();
            console.log(start)
            console.log(end)
            if (start == '' || end == '') {
                layer.msg('开始时间和结束时间不能为空!',function () {
                });
            }else{
                if (start > end) {
                    layer.msg('开始时间不能大于结束时间!',function () {
                });
                }else{
                    loading();
                    $.post('{{ url('admin/order/info') }}',{
                        "_token" : '{{ csrf_token() }}',
                        "start" : start,
                        "end" : end,
                        "day" : "more"
                    },function (ret) {
                        var obj = $.parseJSON(ret);
                            layer.close(loadingBox);
                            if (obj.status == 'success') {
                                console.log(obj.data)
                                $('#order').text(obj.data.order+' 单');
                                $('#totalNum').text(obj.data.totalNum+' 件');
                                $('#total').text(obj.data.total+' 积分/元');
                                $('#pointAll').text(obj.data.pointAll+' 积分');
                                $('#priceAll').text(obj.data.priceAll+' 元');
                                var html = '<tr>\
                        <th class="tc">ID序</th>\
                        <th class="tc">商品名称</th>\
                        <th class="tc">属性ID</th>\
                        <th class="tc">商品图片</th>\
                        <th class="tc">单品销售额</th>\
                        <th class="tc">销售数量</th>\
                    </tr>';
                                $.each(obj.data.goods, function(index, val) {
                                    html += '<tr><td class="tc">'+index+'</td><td class="tc">'+val.goods_name+'</td><td class="tc">'+val.goods_attr+'</td><td class="tc"><img style="width:3rem;border-radius: none;" src="http://jaclub.shareshenghuo.com/'+val.goods_pic+'"></td><td class="tc">'+val.count_point+'</td><td class="tc">'+val.count+'</td></tr>'
                                });
                                $('#goodsList').html(html);
                            }else{
                                layer.msg(obj.msg,function () {
                                    location.reload();
                                });
                            }
                    });
                }
            }
        }

        function suba() {
            var starta = $('input[name=starta]').val();
            if (starta == '') {
                layer.msg('开始时间和结束时间不能为空!',function () {
                });
            }else{
                // if (start > end) {
                //     layer.msg('开始时间不能大于结束时间!',function () {
                // });
                var start = starta;
                var end = starta+" 23:59:59";
                    loading();
                    $.post('{{ url('admin/order/info') }}',{
                        "_token" : '{{ csrf_token() }}',
                        "start" : start,
                        "end" : end,
                        "day" : "one"
                    },function (ret) {
                        var obj = $.parseJSON(ret);
                            layer.close(loadingBox);
                            if (obj.status == 'success') {
                                console.log(obj.data)
                                $('#order').text(obj.data.order+' 单');
                                $('#totalNum').text(obj.data.totalNum+' 件');
                                $('#total').text(obj.data.total+' 积分/元');
                                $('#pointAll').text(obj.data.pointAll+' 积分');
                                $('#priceAll').text(obj.data.priceAll+' 元');
                                var html = '<tr>\
                        <th class="tc">ID序</th>\
                        <th class="tc">商品名称</th>\
                        <th class="tc">属性ID</th>\
                        <th class="tc">商品图片</th>\
                        <th class="tc">现金价格</th>\
                        <th class="tc">积分价格</th>\
                        <th class="tc">销售数量</th>\
                        <th class="tc">销售时间</th>\
                        <th class="tc">订单变动时间</th>\
                    </tr>';
                                $.each(obj.data.goods, function(index, val) {
                                    var num = parseInt(val.goods_num);
                                    var price = parseFloat(val.price)/num;
                                    var point = parseFloat(val.point)/num;
                                    html += '<tr><td class="tc">'+index+'</td><td class="tc">'+val.goods_name+'</td><td class="tc">'+val.goods_attr+'</td><td class="tc"><img style="width:3rem;border-radius: none;" src="http://jaclub.shareshenghuo.com/'+val.goods_pic+'"></td><td class="tc">'+price+'</td><td class="tc">'+point+'</td><td class="tc">'+val.count+'</td><td>'+val.created_at+'</td><td>'+val.created_at+'</td>/tr>'
                                });
                                $('#goodsList').html(html);
                            }else{
                                layer.msg(obj.msg,function () {
                                    location.reload();
                                });
                            }
                    });
            }
        }
    </script>
@endsection