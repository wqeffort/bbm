@extends('lib.home.header-goods')
@section('body')
<style>
    /*控制轮播进度条*/
    .focus {
        display: block;
    }
    /*控制侧边栏titile*/
    .mm-navbar-top {
        display: none;
    }
    .backBtn div {
        width: 2rem;
        height: 2rem;
        background: #FFF;
        z-index: 999;
        position: fixed;
        line-height: 2rem;
        text-align: center;
        border-radius: 50%;
        font-size: 1.5rem;
        left: 1rem;
        top: 1rem;
        color: #000;
        opacity: .7;
    }
    .nav {
       background-color: #fff;
       text-align: center;
      }
      .nav .tab {
       position: relative;
       overflow: hidden;
       display: flex;
       justify-content: space-between;
        margin-bottom: .5rem;
}
      .tab ol {
       height: 2.56rem;
       line-height:2.56rem;
       display: inline-block;
       /*border-right: 1px solid #e1e1e1;*/
      }
      .tab ol:last-child {
       border-right: 0;
      }
      .tab .curr {
       border-bottom: 2px solid #fc7831;
       color: #fc7831;
      }
      .content ul li {
       display: none;
       width: 100%;
       position: relative;
      }
      .list img {
          width: 100%;
        vertical-align: middle;
      }
</style>
<div id="navigation" style="background: #FFF;  padding-top: 1rem;">
    <div class="nav">
        <div class="tab border-b">
            <ol style="width:50%;" rel="external nofollow" rel="external nofollow" class="curr cur0">商品详情</ol>
            <ol style="width:50%;" rel="external nofollow" rel="external nofollow" class="curr cur1">商品规格</ol>
        </div>
    </div>
</div>
<!-- banner Start -->
<div>
    <img style="width: 100%;" src="{{ url($goods->goods_pic) }}" alt="">
</div>
{{-- <div class="slider">
    {{-- <ul>
        @foreach ($goodsGallery as $element)
            <li>
                <a href="javascript:;"><img src="{{ url($element) }}"></a>
            </li>
        @endforeach
    </ul> --}}
    
    <script type="text/javascript" src="{{ asset('js/yxMobileSlider.js') }}"></script>
    <script type="text/javascript">
        var height = $(window).height();
        var width = $(window).width();
        // 获取屏幕的高度
        $(".slider").yxMobileSlider({width:width,height:width,during:3000})
    </script>
    {{-- 顶部漂浮按钮  --}}
    <a class="backBtn" href="javascript:;" onClick="javascript :history.back(-1);">
        <div><i class="fa fa-angle-left"></i></div>
    </a>
</div> --}}
<!-- banner End -->

<div class="goods_info">
    <div class="goods_name">
        <h1>{{ $goods->goods_name }}</h1>
    </div>
    <div class="goods_title">
        <span>{{ $goods->goods_title }}</span>
    </div>
    <div class="goods_price">
        <p>
            ¥ <b id="price">{{ $goods->goods_price }}</b>
        </p>
    </div>
    <div class="goods_other">
        <ul>
            <li>
                <span>积分价格:</span>
                <b id="point">{{ $goods->goods_point }}</b>
                积分
            </li>
            <li></li>
        </ul>
        <ul>
            <li>
                <span  style="display: none;">剩余库存:</span>
                <b id="depot" style="display: none;">{{ $depot - $sell }}</b>
            </li>
            <li>
                <span>销售数量:</span>
                <b>
                    @if ($goods->id == 171)
                        {{ $sell }}
                    @else
                        @if ($sell == 0)
                            @if ($goods->goods_point > 10000)
                                {{ abs(intval($goods->goods_point/ 1000 - 10 + 2)) }}
                            @else
                                {{ abs(intval(10 - $goods->goods_point / 1000 + 2)) }}
                            @endif
                        @elseif ($sell < 10)
                            @if ($goods->id > 99)
                                {{ abs((intval(10 - $goods->id / 10) + 2)) * 3 }}
                            @else
                                {{ abs((intval(10 - $goods->id / 10) + 5)) * 3 }}
                            @endif
                        @else
                            @if ($goods->id > 99)
                                {{ abs((intval(10 - $goods->id / 10) + 5 + $sell)) * 5 }}
                            @else
                                {{ abs((intval(10 - $goods->id / 10) + 5 + $sell)) * 8 }}
                            @endif
                        @endif
                    @endif
                </b>
                件
            </li>
        </ul>
    </div>
    <div class="blank"></div>
    <div class="goods_attr">
         <ul class="goods_attr_btn" @if (!empty($attr))
         @foreach ($attr as $item)
             @if (!empty($item['attr']))
                @foreach ($item['attr'] as $k => $v)
                    @if ($v['attr_name'] == '无属性')
                        style="display:none;"
                    @endif
                @endforeach
             @endif
         @endforeach
         @endif onclick="getAttr()">
             <li style="line-height: 2rem;">请选择商品属性</li>
             <li style="line-height: 2rem;"><i class="fa fa-angle-right"></i></li>
         </ul>
         <div class="goods_attr_info" style="display: none;">
            <ul>
                @if (!empty($attr))
                    @foreach ($attr as $key => $value)
                        <p>请选择 {{ $value['attr_name'] }}</p>
                        <li>
                        @if (!empty($value['attr']))
                            @foreach ($value['attr'] as $k => $element)
                                @if ($k == 0)
                                    <input type="radio" name="attr{{ $key }}" id="attr{{ $element['id'] }}" onchange="handleAttr()" value="{{ $element['id'] }}" checked>
                                    <label for="attr{{ $element['id'] }}">{{ $element['attr_name'] }}</label>
                                @else
                                <input type="radio" name="attr{{ $key }}" id="attr{{ $element['id'] }}" onchange="handleAttr()" value="{{ $element['id'] }}" >
                                <label for="attr{{ $element['id'] }}">{{ $element['attr_name'] }}</label>
                                @endif
                            @endforeach
                        @endif
                        </li>
                    @endforeach
                @endif
            </ul>
         </div>
    </div>
    <div class="blank"></div>
    <div class="goods_num">
        <ul>
            <li style="line-height: 2rem;">请选择购买数量</li>
            <li style="line-height: 2rem;">
                <i class="fa fa-minus-square" onclick="down()"></i>
                <input type="number" name="num" value="1" style="width: 2rem;text-align: center;">
                <i class="fa fa-plus-square" onclick="up()"></i>
            </li>
        </ul>
    </div>
    <div class="blank"></div>
    <div class="nav">
        <div class="tab border-b">
            <ol style="width:50%;" rel="external nofollow" rel="external nofollow" class="curr cur0">商品详情</ol>
            <ol style="width:50%;" rel="external nofollow" rel="external nofollow" class="curr cur1">商品规格</ol>
        </div>
        <div class="content">
            <ul>
                <li class="tet0" style="display: block">
                    {{-- <h3>商品详情</h3> --}}
                    <div class="list">
                        {!! $goods->goods_desc !!}
                    </div>
                </li>
                <li class="tet1">
                    {{-- <h3>商品规格</h3> --}}
                    <div class="list" style="padding: 0 1rem;">
                        {!! $goods->goods_server !!}
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div style="height: 20rem;"></div>
</div>
<div class="goods_nav_bottom">
    <ul>
        <li style="width: 25%;" onclick="server()">
            <span><i class="fa fa-commenting"></i></span>
            <p>客服</p>
        </li>
        <li style="width: 25%;" onclick="collection()">
            @if ($collection)
                <span><i class="fa fa-star"></i></span>
                <p>取消收藏</p>
            @else
                <span><i class="fa fa-star-o"></i></span>
                <p>收藏</p>
            @endif
            
        </li>
        <li style="width: 50%;background: #C40000;line-height: 1.2rem;padding: 0.3rem;" onclick="endFlow()">
            <span><b id="totalPrice">{{ $goods->goods_price }}</b> 元  (<span style="font-size: .6rem" id="totalPoint">{{ $goods->goods_point }} 积分</span>)</span>
            <h4 style="font-size: 1rem;">立即购买</h4>
        </li>
    </ul>
</div>

<script type="text/javascript">
// 定义属性开关
var display = 1;
$(function () {
    $('.cur1').css({
        'border-bottom': 'none',
        'color': '#333'
    });
})



// 打开属性选项框子
function getAttr() {
    // console.log(display);
    if (display % 2 == 0) {
        $('.goods_attr_info').css('display','none');
        display = display + 1;
    }else{
        $('.goods_attr_info').css('display','block');
        display = display + 1;
    }
}

// 选择属性控制价格
function handleAttr(attrId) {
    var array = [];
    $.each($('input:checked'), function(index, val) {
         array[index] = val.value;
    });
    // console.log(array);
    var num = $('input[name=num]').val();
    var price = '{{ $goods->goods_price }}';
    var point = '{{ $goods->goods_point }}'
    // 获取属性附加价格,进行赋值操作
    load();
    $.post('{{ url('getArrayAttr') }}', {
        "_token" : '{{ csrf_token() }}',
        "array" : array
    }, function (ret) {
        var obj = $.parseJSON(ret);
        layer.close(loadBox);
        if (obj.status == 'success') {
            console.log(obj.data);
            if (obj.data.depot == 0) {
                layer.open({
                    content: '该商品已经没有库存了,请勿购买!'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
            // 进行赋值操作
            var newPrice = ((parseFloat(obj.data.attrPrice)+ parseFloat(price)) * parseInt(num)).toFixed(2);
            var newPoint = ((parseInt(obj.data.attrPoint) + parseInt(point)) * parseInt(num)).toFixed(2);
            $('#totalPrice').text(parseFloat(newPrice));
            $('#totalPoint').text(parseFloat(newPoint) + ' 积分');
            $('#depot').text(obj.data.depot);
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    });
}




// 数量加减操作
function up() {
    var num = $('input[name=num]').val();
    var totalPrice = $('#totalPrice').text();
    var totalPoint = $('#totalPoint').text();
    var newNum = parseInt(num) + 1;
    // 获取库存
    var depot = $('#depot').text();
    // if (newNum > parseInt(depot)) {
    //     layer.open({
    //         content: '你购买的数量超过了商家的库存'
    //         ,skin: 'msg'
    //         ,time: 2 //2秒后自动关闭
    //     });
    // }else{
        var newPrice = parseFloat(totalPrice) / parseInt(num) * newNum;
        var newPoint = parseFloat(totalPoint) / parseInt(num) * newNum;
        $('input[name=num]').val(newNum);
        $('#totalPrice').text(parseFloat(newPrice));
        $('#totalPoint').text(parseFloat(newPoint));
    // }
}
function down() {
    var num = $('input[name=num]').val();
    var totalPrice = $('#totalPrice').text();
    var totalPoint = $('#totalPoint').text();
    var newNum = parseInt(num) - 1;
    if (newNum < 1) {
        layer.open({
            content: '购买的数量不能为0'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        var newPrice = parseFloat(totalPrice) / parseInt(num) * newNum;
        var newPoint = parseFloat(totalPoint) / parseInt(num) * newNum;
        $('input[name=num]').val(newNum);
        $('#totalPrice').text(parseFloat(newPrice));
        $('#totalPoint').text(parseFloat(newPoint));
    }
}

// 收藏操作
function collection() {
    load();
    $.post('{{ url('addCollection') }}', {
        "_token" : '{{ csrf_token() }}',
        "goodsId" : '{{ $goods->id }}'
    }, function (ret) {
        var obj = $.parseJSON(ret);
        layer.close(loadBox);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            location.reload();
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}


// 点击结算按钮弹出层
function endFlow() {
    // 获取到当前商品的属性
    var num = $('input[name=num]').val();
    var totalPrice = $('#totalPrice').text();
    var totalPoint = $('#totalPoint').text();
    var html = '<li>你选择的商品参数为:</li>';
    var array = [];
    $.each($('input:checked'), function(index, val) {
         array[index] = val.value;
    });
    load();
    $.post('{{ url('getArrayAttr') }}', {
        "_token" : '{{ csrf_token() }}',
        "array" : array
    }, function (ret) {
        var obj = $.parseJSON(ret);
        layer.close(loadBox);
        if (obj.status == 'success') {
            $.each(obj.data.attrName, function (index,val) {
                html += '<li style="background: #C40000;color: #FFF;padding: .1rem .5rem;font-size: .8rem;border-radius: .3rem;">'+val+'</li>'
            })
            console.log(array);
            layer.open({
                type: 1
                ,content: '<div style="padding:.5rem;">\
                    <ul style="display: flex;justify-content: space-between;">\
                        <li style="width: 50%;text-align: center;">\
                            <img style="width:90%;" src="{{ url($goods->goods_pic) }}" alt="" />\
                        </li>\
                        <li style="width: 50%;">\
                            <dl>\
                                <dt style="font-size: 1rem;font-weight: bold;    height: 3rem;margin-top: .5rem;">{{ $goods->goods_name }}</dt>\
                                <dd style="line-height:2rem;color: #C40000;">结算金额: <b>'+totalPrice+'</b> 元</dd>\
                                <dd style="line-height:2rem;color: #C40000;">结算积分: <b>'+parseInt(totalPoint)+'</b> 积分</dd>\
                                <dd style="line-height:2rem;color: #C40000;">购买数量: <b>'+num+'</b> 件</dd>\
                            </dl>\
                        </li>\
                    </ul>\
                    <div style="text-align:center;margin-top:2rem;">\
                        <a onclick="addCar([\''+array+'\'],{{ $goods->id }},'+num+')" style="background: #C40000;color: #FFF;padding: .5rem 1rem;" href="javascript:;">确认商品信息</a>\
                    </div>\
                </div>'
                ,anim: 'up'
                ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 280px; border:none;'
            });
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    });

}

function addCar(attrArray,goodsId,goodsNum) {
    // 获取用户选择的商品参数,添加进入购物车
    load();
    console.log(attrArray);
    $.post('{{ url('addCar') }}', {
        "_token" : '{{ csrf_token() }}',
        "attr_array" : attrArray,
        "goods_id" : goodsId,
        "goods_num" : goodsNum
    }, function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            //询问框
            layer.open({
                content: '亲爱的Ja Club会员,请您选择下一步的操作'
                ,btn: ['进入购物车结算', '继续浏览商品']
                ,yes: function(index){
                    location.href="{{ url('car') }}";
                }
                ,no: function (ret) {
                    layer.closeAll();
                }
            });
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    });
}

// 头部搜索框漂浮
if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage('resize', "*");
}
$(window).scroll(function(){
    // 改变这一目标以不同的百分比
    var targetPercentage = 18;
    //更改此设置您的资产净值栏的高度，所以它隐藏适当。如果你有一个框阴影，你可能不得不调整这个数字是高度+阴影距离
    var navBarHeight = 70;
    //将此更改为您要显示的内容的id。
    var targetID = "#navigation";
    //Window Math
    var scrollTo = $(window).scrollTop(),
    docHeight = $(document).height(),
    windowHeight = $(window).height();
    scrollPercent = (scrollTo / (docHeight-windowHeight)) * 100;
    scrollPercent = scrollPercent.toFixed(1);

    if(scrollPercent > targetPercentage) {
        $(targetID).css({ top: '0' });
    }

    if(scrollPercent < targetPercentage) {
        $(targetID).css({ top: '-'+navBarHeight+'px' });
    }

}).trigger('scroll');

$(function() {
    $(".tab ol").click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        var index = $(this).index();
        number = index;
        $('.nav .content ul li').hide();
        // $('.nav .content ul li:eq(' + number + ')').show();
        if (index == 0) {
            $('.tet0').css('display','block');
            $('.cur0').css({
                'border-bottom': '2px solid #fc7831;',
                'color': '#fc7831'
            });
            $('.cur1').css({
                'border-bottom': '#FFF',
                'color': '#333'
            });
        }else if (index == 1) {
            $('.tet1').css('display','block');
            $('.cur0').css({
                'border-bottom': '#FFF',
                'color': '#333'
            });
            $('.cur1').css({
                'border-bottom': '2px solid #fc7831;',
                'color': '#fc7831'
            });
        }
        // else if (index == 2) {
        //     $('.tet2').css('display','block');
        // }
    });
})

function server() {
    layer.open({
        content: '客服功能正在开发之中.如需要帮助,请您拨打人工客服热线:400-967-0003.我们将竭诚为您服务!'
        ,btn: '我知道了'
    });
}
</script>
@endsection
