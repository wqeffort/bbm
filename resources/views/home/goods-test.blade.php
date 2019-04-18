@extends('lib.home.header')
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
        position: absolute;
        line-height: 2rem;
        text-align: center;
        border-radius: 50%;
        font-size: 1.5rem;
        left: 1rem;
        top: 1rem;
        color: #000;
        opacity: .7;
    }
</style>
<div id="navigation" style="background: #FFF">
    <a href="#wrap" style="color: #000;">
        <ul class="tabClick">
            <li class="">商品详情</li>
            {{-- <li class="">热门评论</li> --}}
            <li class="">商品规格</li>
            {{-- <li class="">购买须知</li> --}}
        </ul>
    </a>
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
    <a class="backBtn">
        {{-- <div><i class="fa fa-angle-left"></i></div> --}}
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
                <span>剩余库存:</span>
                <b id="depot">{{ $goods->goods_point }}</b>
                件
            </li>
            <li>
                <span>销售数量:</span>
                <b>{{ $goods->goods_point }}</b>
                件
            </li>
        </ul>
    </div>
    <div class="blank"></div>
    <div class="goods_attr">
         <ul class="goods_attr_btn" onclick="getAttr()">
             <li>请选择商品属性</li>
             <li><i class="fa fa-angle-right"></i></li>
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
            <li>请选择购买数量</li>
            <li>
                <i class="fa fa-minus-square" onclick="down()"></i>
                <input type="number" name="num" value="1" style="width: 2rem;text-align: center;">
                <i class="fa fa-plus-square" onclick="up()"></i>
            </li>
        </ul>
    </div>
    <div class="blank"></div>
    <div class="wrap" id="wrap" style="overflow: hidden;">
        <ul class="tabClick">
            <li class="active">商品详情</li>
            {{-- <li>热门评论</li> --}}
            <li>商品规格</li>
            {{-- <li>购买须知</li> --}}
        </ul>
        <div class="lineBorder">
            <div class="lineDiv"><!--移动的div--></div>
        </div>
        <div class="tabCon">
            <div class="tabBox" style="overflow: hidden; position: relative;">
                <div class="tabList" style="float: left; padding: 0px; margin: 0px; vertical-align: top; display: table-cell;">
                    {!! $goods->goods_desc !!}
                </div>
                {{-- <div class="tabList" style="float: left; padding: 0px; margin: 0px; vertical-align: top; display: table-cell;">
                    我是评论
                </div> --}}
                <div class="tabList" style="float: left; padding: 0px; margin: 0px; vertical-align: top; display: table-cell;">
                    {!! $goods->goods_server !!}
                </div>
                {{-- <div class="tabList" style="float: left; padding: 0px; margin: 0px; vertical-align: top; display: table-cell;">
                    购买须知
                </div> --}}
            </div>
        </div>
    </div>
    <div style="height: 20rem;"></div>
</div>
<div class="goods_nav_bottom">
    <ul>
        <li style="width: 25%;">
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
    // console.log(attr);
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
    if (newNum > parseInt(depot)) {
        layer.open({
            content: '你购买的数量超过了商家的库存'
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
                    <ul style="display: flex;justify-content: space-around;flex-wrap: wrap;">\
                        '+ html +'\
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

    // TAB选项卡
    window.onload = function (){
        var windowWidth = $(window).width(); //window 宽度;
        var wrap = document.getElementById('wrap');
        var tabClick = wrap.querySelectorAll('.tabClick')[0];
        var tabLi = tabClick.getElementsByTagName('li');

        var tabBox =  wrap.querySelectorAll('.tabBox')[0];
        var tabList = tabBox.querySelectorAll('.tabList');

        var lineBorder = wrap.querySelectorAll('.lineBorder')[0];
        var lineDiv = lineBorder.querySelectorAll('.lineDiv')[0];

        var tar = 0;
        var endX = 0;
        var dist = 0;

        tabBox.style.overflow='hidden';
        tabBox.style.position='relative';
        tabBox.style.width=windowWidth*tabList.length+"px";

        for(var i = 0 ;i<tabLi.length; i++ ){
              tabList[i].style.width=windowWidth+"px";
              tabList[i].style.float='left';
              tabList[i].style.float='left';
              tabList[i].style.padding='0';
              tabList[i].style.margin='0';
              tabList[i].style.verticalAlign='top';
              tabList[i].style.display='table-cell';
        }

        for(var i = 0 ;i<tabLi.length; i++ ){
            tabLi[i].start = i;
            tabLi[i].onclick = function(){
                var star = this.start;
                for(var i = 0 ;i<tabLi.length; i++ ){
                    tabLi[i].className='';
                };
                tabLi[star].className='active';
                init.lineAnme(lineDiv,windowWidth/tabLi.length*star)
                init.translate(tabBox,windowWidth,star);
                endX= -star*windowWidth;
            }
        }

        function OnTab(star){
            if(star<0){
                star=0;
            }else if(star>=tabLi.length){
                star=tabLi.length-1
            }
            for(var i = 0 ;i<tabLi.length; i++ ){
                tabLi[i].className='';
            };

             tabLi[star].className='active';
            init.translate(tabBox,windowWidth,star);
            endX= -star*windowWidth;
        };

        tabBox.addEventListener('touchstart',chstart,false);
        tabBox.addEventListener('touchmove',chmove,false);
        tabBox.addEventListener('touchend',chend,false);
        //按下
        function chstart(ev){
            ev.preventDefault;
            var touch = ev.touches[0];
            tar=touch.pageX;
            tabBox.style.webkitTransition='all 0s ease-in-out';
            tabBox.style.transition='all 0s ease-in-out';
        }
        //滑动
        function chmove(ev){
            var stars = wrap.querySelector('.active').start;
            ev.preventDefault;
            var touch = ev.touches[0];
            var distance = touch.pageX-tar;
            dist = distance;
            init.touchs(tabBox,windowWidth,tar,distance,endX);
            init.lineAnme(lineDiv,-dist/tabLi.length-endX/4);
        };
        //离开
        function chend(ev){
            var str= tabBox.style.transform;
            var strs = JSON.stringify(str.split(",",1));
            endX = Number(strs.substr(14,strs.length-18));

            if(endX>0){
                init.back(tabBox,windowWidth,tar,0,0,0.3);
                endX=0
            }else if(endX<-windowWidth*tabList.length+windowWidth){
                endX=-windowWidth*tabList.length+windowWidth
                init.back(tabBox,windowWidth,tar,0,endX,0.3);
            }else if(dist<-windowWidth/3){
                 OnTab(tabClick.querySelector('.active').start+1);
                 init.back(tabBox,windowWidth,tar,0,endX,0.3);
            }else if(dist>windowWidth/3){
                 OnTab(tabClick.querySelector('.active').start-1);
            }else{
                 OnTab(tabClick.querySelector('.active').start);
            }
            var stars = wrap.querySelector('.active').start;
            init.lineAnme(lineDiv,stars*windowWidth/4);
        };
    var init={
        translate:function(obj,windowWidth,star){
            obj.style.webkitTransform='translate3d('+-star*windowWidth+'px,0,0)';
            obj.style.transform='translate3d('+-star*windowWidth+',0,0)px';
            obj.style.webkitTransition='all 0.3s ease-in-out';
            obj.style.transition='all 0.3s ease-in-out';
        },
        touchs:function(obj,windowWidth,tar,distance,endX){
            obj.style.webkitTransform='translate3d('+(distance+endX)+'px,0,0)';
            obj.style.transform='translate3d('+(distance+endX)+',0,0)px';
        },
        lineAnme:function(obj,stance){
            obj.style.webkitTransform='translate3d('+stance+'px,0,0)';
            obj.style.transform='translate3d('+stance+'px,0,0)';
            obj.style.webkitTransition='all 0.1s ease-in-out';
            obj.style.transition='all 0.1s ease-in-out';
        },
        back:function(obj,windowWidth,tar,distance,endX,time){
            obj.style.webkitTransform='translate3d('+(distance+endX)+'px,0,0)';
            obj.style.transform='translate3d('+(distance+endX)+',0,0)px';
            obj.style.webkitTransition='all '+time+'s ease-in-out';
            obj.style.transition='all '+time+'s ease-in-out';
        },
    }

}
// 头部搜索框漂浮
if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage('resize', "*");
}
$(window).scroll(function(){
    // 改变这一目标以不同的百分比
    var targetPercentage = 18;
    //更改此设置您的资产净值栏的高度，所以它隐藏适当。如果你有一个框阴影，你可能不得不调整这个数字是高度+阴影距离
    var navBarHeight = 50;
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
</script>
@endsection
