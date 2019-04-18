@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">订单管理</a> &raquo; 订单列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">查询选项:</th>
                    <td>
                        <select>
                            <option value="">全部</option>
                            <option value="">订单号</option>
                            <option value="">其他</option>
                        </select>
                    </td>
                    <th width="70">关键字:</th>
                    <td><input type="text" name="keywords" placeholder="关键字"></td>
                    <td><input type="submit" name="sub" value="查询"></td>
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
                    <a href="#"><i class="fa fa-plus"></i>未发货订单</a>
                    <a href="#"><i class="fa fa-recycle"></i>已发货订单</a>
                    {{-- <a href="#"><i class="fa fa-refresh"></i>我是按钮</a> --}}
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">订单编号</th>
                        <th class="tc">用户头像</th>
                        <th class="tc">用户昵称</th>
                        <th class="tc">用户等级</th>
                        <th class="tc">商品图片</th>
                        <th class="tc">商品名称</th>
                        <th class="tc">商品属性</th>
                        <th class="tc">购买数量</th>
                        <th class="tc">支付方式</th>
                        <th class="tc">支付金额</th>
                        <th class="tc">快递信息</th>
                        <th class="tc">订单状态</th>
                        <th class="tc">下单时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($data)
                        @foreach ($data as $value)
                        @if ($value->status == 1 && $value->end == 0)
                            <tr style="background: #fafba8;">
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->num }}</td>
                            <td class="tc"><img class="user_pic" src="{{ asset($value->user_pic) }}"></td>
                            <td class="tc">{{ $value->user_nickname }}</td>
                            <td class="tc">{{ $value->user_rank }}</td>
                            <td class="tc"><img class="user_pic" style="border-radius:0;" src="{{ asset($value->goods_pic) }}"></td>
                            <td class="tc">{{ $value->goods_name }}</td>
                            <td class="tc">
                                @if ($value->attr)
                                    @foreach ($value->attr as $element)
                                        <span>{{ $element }}</span>|
                                    @endforeach
                                @endif
                            </td>
                            <td class="tc">{{ $value->goods_num }}</td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(0)
                                        <span style="color:#C40000;">未知状态</span>
                                        @break
                                    @case(1)
                                        <span style="color: #27AF00;">微信支付</span>
                                        @break
                                    @case(2)
                                        <span style="color: blue;">积分支付</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(0)
                                        <span>{{ $value->point }} 积分,{{ $value->price }} 元</span>
                                        @break
                                    @case(1)
                                        <span>{{ $value->price }} 元</span>
                                        @break
                                    @case(2)
                                        <span>{{ $value->point }} 积分</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">
                                @if ($value->express)
                                    <span style="color: #27AF00">{{ $value->express }}</span>
                                @else
                                    <span style="color:#C40000;">未发货</span>
                                @endif
                            </td>
                            <td class="tc">
                                @switch($value->end)
                                    @case(0)
                                        <span style="color:#C40000;">订单未完结</span>
                                        @break
                                    @case(2)
                                        <span style="color:#27AF00;">订单已完结</span>
                                        @break
                                    @case(0)
                                        <span style="color#666">订单未发货</span>
                                        @break
                                @endswitch
                                
                            </td>
                            <td class="tc">
                                {{ $value->created_at }}
                            </td>
                            <td class="tc">
                                <a href="javascript:;" onclick="getOrderInfo('{{ $value->num }}')"><i class="fa fa-plane" aria-hidden="true"></i></a>
                                <a href="javascript:;" onclick="printGoodsList('{{ $value->num }}')"><i class="fa fa-print" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->num }}</td>
                            <td class="tc"><img class="user_pic" src="{{ asset($value->user_pic) }}"></td>
                            <td class="tc">{{ $value->user_nickname }}</td>
                            <td class="tc">{{ $value->user_rank }}</td>
                            <td class="tc"><img class="user_pic" style="border-radius:0;" src="{{ asset($value->goods_pic) }}"></td>
                            <td class="tc">{{ $value->goods_name }}</td>
                            <td class="tc">
                                @if ($value->attr)
                                    @foreach ($value->attr as $element)
                                        <span>{{ $element }}</span>|
                                    @endforeach
                                @endif
                            </td>
                            <td class="tc">{{ $value->goods_num }}</td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(0)
                                        <span style="color:#C40000;">未知状态</span>
                                        @break
                                    @case(1)
                                        <span style="color: #27AF00;">微信支付</span>
                                        @break
                                    @case(2)
                                        <span style="color: blue;">积分支付</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(0)
                                        <span>{{ $value->point }} 积分,{{ $value->price }} 元</span>
                                        @break
                                    @case(1)
                                        <span>{{ $value->price }} 元</span>
                                        @break
                                    @case(2)
                                        <span>{{ $value->point }} 积分</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">
                                @if ($value->express)
                                    <span style="color: #27AF00">{{ $value->express }}</span>
                                @else
                                    <span style="color:#C40000;">未发货</span>
                                @endif
                            </td>
                            <td class="tc">
                                @switch($value->end)
                                    @case(0)
                                        <span style="color:#C40000;">订单未完结</span>
                                        @break
                                    @case(2)
                                        <span style="color:#27AF00;">订单已完结</span>
                                        @break
                                    @case(0)
                                        <span style="color#666">订单未发货</span>
                                        @break
                                @endswitch
                                
                            </td>
                            <td class="tc">
                                {{ $value->created_at }}
                            </td>
                            <td class="tc">
                                <a href="javascript:;" onclick="getOrderInfo('{{ $value->num }}')"><i class="fa fa-plane" aria-hidden="true"></i></a>
                                <a href="{{ url('admin/order/printGoodsList') }}/{{ $value->num }}"><i class="fa fa-print" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        @endif
                        
                        @endforeach
                    @endif
                </table>
                <div class="page_list">
                    {!! $order->links() !!}
                </div>

            </div>
        </div>
    </form>
<script type="text/javascript">
// 发送请求获取商品信息
function getOrderInfo(num) {
    loading();
    $.post('{{ url('admin/order/getOrderInfo') }}',{
        "_token" : '{{ csrf_token() }}',
        "num" : num
    },function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.close(loadingBox);
            var html ='';
            console.log(obj.data);
            var express_type = expressType(obj.data.express_type);
            var express_status = expressStatus(obj.data.express_status);
            $.each(obj.data.data, function(index, val) {
                if (val.type == '1') {
                    var text_type = '<span style="padding: .2rem .5rem;background:#333;color:#FFF;">微信支付</span>';
                }else{
                    var text_type = '<span style="padding: .2rem .5rem;background:#333;color:#FFF;">积分支付</span>';
                }
                var attr = '';
                $.each(val.attr, function(i, v) {
                     attr += '<span style="padding: .2rem .5rem;background: #C40000;color: #FFF;margin-left: 1rem;">'+v+'</span>'
                });
                 html += '<dd><ul style="display: flex;justify-content: start;padding: 1rem;border-left: 5px solid #666;margin-top: 1rem;"><li><img style="width:8rem; padding-right:2rem;" src="/'+val.goods_pic+'" /></li><li style="line-height: 1.7rem;"><h3 style="font-weight:bold;">'+val.goods_name+'</h3><p>商品单价: '+val.price/val.goods_num+' 元 <span>( '+val.point/val.goods_num+' 积分)</span></p><p>购买数量: '+val.goods_num+' '+attr+'</p><p>合计总价:'+val.price+' 元 <span>(积分: '+val.point+' 积分)</span></p><p>用户支付方式: '+text_type+'</p></li></ul></dd>';
            });
            if (obj.data.express_type == '0' ) {
                var span = '<a onclick=printExpressBox("'+obj.data.num+'") style="height: 2rem;line-height: 2rem;margin: .5rem 0;padding: 0 1rem;background: #FFF;color:#333;"><i class="fa fa-circle-o-notch fa-spin"></i> 请求发货单</a>';
            }else{
                var span = '<a onclick=getNewExpressStatus("'+obj.data.num+'") style="height: 2rem;line-height: 2rem;margin: .5rem 0;padding: 0 1rem;background: #FFF;color:#333;"><i class="fa fa-circle-o-notch fa-spin"></i> 更新发货单</a>';
            }
            if (obj.data.express) {
                var bottomNav = '<span style="margin-left: 2rem;">快递单号: <b style="background: #FFF;color: #333;padding: .5rem 1rem;">'+obj.data.express+'</b></span> <span><a style="padding: .5rem 1rem;background: #FFF;color: #333;margin-right: 2rem;" href="javascript:;">点击发货</a></span>';
            }else{
                var bottomNav = '<span style="margin-left: 2rem;">快递单号: <b style="background: #FFF;color: #333;padding: .5rem 1rem;">请先请求发货单</b></span> <span></span>';
            }
            // 展示商品详细信息
            layer.open({
                type: 1,
                closeBtn: 0, //不显示关闭按钮
                title: false,
                anim: 2,
                shadeClose: true, //开启遮罩关闭
                skin: 'layui-layer-demo', //加上边框
                area: ['50%', '800px'], //宽高
                content: '<div><div style="height:3rem;background:#333;padding:0 3rem;line-height:3rem;display: flex;justify-content: space-between;"><h2 style="color:#FFF;font-size">订单编号: '+obj.data.num+'</h2>'+span+'</div><div style="padding: 1rem 2rem 2rem 2rem;"><dl><dt style="line-height: 2rem;"><h3 style="font-size:1rem;font-weight: bold;">订单详情<a href="javascript:;" style="margin: 0 1rem;color: #C40000;" onclick=printExpressList("'+obj.data.num+'")><i class="fa fa-print"> 打印快递单</i></a>('+express_type+'['+express_status+'])</h3><ul><li>收 货 人: '+obj.data.name+'</li><li>联系电话: '+obj.data.phone+'</li><li>收货地址:'+obj.data.ads+'</li><li>订单备注:'+obj.data.mark+'</li></ul></dt>'+html+'</dl></div><ol style="position: absolute;bottom: 0;width: 100%;display: flex;justify-content: space-between;color: #FFF;background: #333;padding: 1rem 0;">'+bottomNav+'</ol></div>'
            });
        }else{
            layer.msg(obj.msg,function () {
                // body...
            })
        }
    })
}

function printExpressBox(orderNum) {
    layer.open({
      type: 1,
      skin: 'layui-layer-rim', //加上边框
      area: ['50%', '800px'], //宽高
      title: '填写订单信息',
      content: '<div style="padding: 3rem;"><ul>\
        <li style="padding: 1rem 0;">配送方式: <select name="expressType" id="expressType">\
            <option value="2">顺丰特惠</option>\
            <option value="1">顺丰标快</option>\
            <option value="3">电商特惠</option>\
            <option value="5">顺丰次晨</option>\
            <option value="6">顺丰即日</option>\
            <option value="7">电商速配</option>\
            <option value="15">生鲜速配</option>\
            </select>\
        </li>\
        <li style="padding: 1rem 0;">\
            <p>包裹数量: <input id="package" style="width: 2rem;text-align: center;" type="number" value="1" /></p>\
        </li>\
        <li style="padding: 1rem 0;">订单备注: <textarea name="mark" id="mark" cols="20" rows="15"></textarea><span style="color:#999;">禁止超过三十个中文字符</span></li>\
        </ul>\
        <p style="text-align:center;margin-top: 2rem;"><a style="padding: .5rem 1rem;background: #C40000;color: #FFF;" href="javascript:;" onclick=sub("'+orderNum+'")>提交请求订单</a></p>\
        </div>'
    });
}

function getNewExpressStatus(orderNum) {
    loading();
    $.post('{{ url('express/orderQuery') }}/'+orderNum+'',{
        "_token" : '{{ csrf_token() }}'
    },function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.closeAll();
            layer.msg(obj.msg);
            setTimeout(function () {
                location.reload();
            },1500)
        }else{
            layer.close(loadingBox);
            layer.msg(obj.msg,function () {
                // body...
            })
        }
    });
}

function sub(orderNum) {
    loading();
    var expressType = $('#expressType option:selected').val();
    var mark = $('#mark').val();
    var package = $('#package').val();
    // console.log(orderNum);
    // console.log(expressType);
    // console.log(mark);
    // console.log(package);
    $.post('{{ url('express/getExpressInfo') }}', {
        "_token" : '{{ csrf_token() }}',
        "orderNum" : orderNum,
        "expressType" : expressType,
        "mark" : mark,
        "package" : package
    }, function (ret) {
        layer.close(loadingBox);
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.msg(obj.msg);
        }else{
            layer.msg(obj.msg,function () {
                // body...
            })
        }
    })
}

// 打印装箱清单
function printGoodsList(orderNum) {
    layer.open({
        type: 2,
        title: false,
        closeBtn: 0, //不显示关闭按钮
        shade: [0],
        area: ['340px', '215px'],
        offset: 'rb', //右下角弹出
        time: 2000, //2秒后自动关闭
        anim: 2,
        content: '正在为您查询订单,请稍等', //iframe的url，no代表不显示滚动条
        end: function(){ //此处用于演示
            layer.open({
            type: 2,
            title: '订单预览',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['80%', '80%'],
            content: '{{ url('admin/order/printGoodsList') }}/'+orderNum+''
        });
      }
    });
}
// 打印快递单
function printExpressList(orderNum) {
    load();
    $.post('{{ url('express/getExpressImg') }}/'+orderNum, {
        "_token" : '{{ csrf_token() }}'
    }, function(ret) {
        var obj = $.parseJSON(ret);
        console.log(obj);
        if (obj.status == 'success') {

        }
    });
}
</script>
@endsection