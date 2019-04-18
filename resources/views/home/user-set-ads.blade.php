@extends('lib.home.header')
@section('body')
<script type="text/javascript" src="{{ asset('js/Popt.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/city.json.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/citySet.js') }}"></script>
    <style type="text/css">
        body {
            background: #EEE;
        }
        dt {
            text-align: center;
            background: #C40000;
            color: #FFF;
            padding: .7rem 0;
            font-size: 1rem;
        }
        dd {
            margin: 1rem;
            border-bottom: 1px solid #999;
            display: flex;
            justify-content: space-between;
        }
        input {
            text-align: center;
            border:none;
            border-radius: none;
        }
        li {
            background: #FFF;
            margin-bottom: .5rem;
            padding: .5rem;
            margin: .5rem;
        }
        li p span {
            color: #666;
        }
    </style>
<ul>
    <li>
        <p style="background: #666;color: #FFF;padding: .5rem;">通讯地址设置:</p>
        <dd>
            选择省市区:
            <input style="border: none;width: 65%;" class="input" name="city" id="city" type="text" placeholder="点击选择省市区" autocomplete="off" value="{{ $user->province }}/{{ $user->city }}/{{ $user->area }}" readonly="true"/><s></s>
        </dd>
        <dd>
            详细地址:
            <input style="border: none;width: 65%;" placeholder="请输入详细地址" type="text" name="ads" value="{{ $user->ads }}" id="ads">
        </dd>
        <p style="text-align: right;margin-right: 1rem;font-size: .8rem;">
            <span>
                <i class="fa fa-info-circle" aria-hidden="true"></i> 该地址为用户通讯地址
            </span>
        </p>
        <div onclick="editUserAds()" style="text-align: center;margin: .5rem;line-height: 2rem;background: #999;color: #FFF;border-radius: .5rem;">
            @if ($user->user_ads)
                修改通讯地址
            @else
                保存通讯地址
            @endif
        </div>
    </li>
    <li>
        <p style="background: #666;color: #FFF;padding: .5rem;">收货地址设置:</p>
        <ul>
            @if ($ads->isNotEmpty())
                @foreach ($ads as $key => $element)
                    <li>
                        <dd>
                            收件人姓名:
                            <input style="border: none;width: 65%;" type="text" value="{{ $element->name }}" disabled="disabled">
                        </dd>
                        <dd>
                            收件人电话:
                            <input style="border: none;width: 65%;" type="number" value="{{ $element->phone }}" disabled="disabled">
                        </dd>
                        <dd>
                            省市区地址:
                            <input style="border: none;width: 65%;" class="input" type="text" value="{{ $element->province}} {{ $element->city }} {{ $element->area }}" disabled="disabled" autocomplete="off" readonly="true"/><s></s>
                        </dd>
                        <dd>
                            详细街道地址:
                            <input style="border: none;width: 65%;" disabled="disabled" value="{{ $element->ads }}" type="text" >
                        </dd>
                        <p style="padding: .5rem;display: flex;justify-content: space-between;">
                            <span onclick="adsDel({{ $element->id }})" style="border: 1px solid #666;padding: .2rem;border-radius: .2rem;">
                                <i class="fa fa-times-circle"></i> 删除地址
                            </span>

                            <span onclick="adsEdit({{ $element->id }})" style="border: 1px solid #666;padding: .2rem;border-radius: .2rem;">
                                <i class="fa fa-times-circle"></i> 编辑地址
                            </span>

                            @if ($element->status == 1)
                                <span style=" color:#000; border: 1px solid #666;padding: .2rem;border-radius: .2rem;"><i class="fa fa-check-circle"></i> 默认地址</span>
                            @else
                                <span onclick="adsStatus({{ $element->id }})" style="border: 1px solid #666;padding: .2rem;border-radius: .2rem;"><i class="fa fa-check-circle"></i> 设为默认</span>
                            @endif
                        </p>
                    </li>
                    <div class="blank"></div>
                @endforeach
            @endif
            <li>
                <div onclick="addAds()" style="text-align: center;margin: .5rem;line-height: 2rem;background: #999;color: #FFF;border-radius: .5rem;">+ 添加新的收货地址</div>
            </li>
        </ul>
    </li>
</ul>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>

<div class="nav-bottom">
    <a href="{{ url('shop') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">服 务</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('article') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">资 讯</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('car') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">购物车</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('user') }}" class="nav-bottom-item false" ng-repeat="i in pages">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>
<div id="la">
    <div id="gesturepwd" style="display: none"></div>
</div>
<script type="text/javascript">
// 地区加载插件
$("#city").click(function (e) {
    SelCity(this,e);
});
$("s").click(function (e) {
    SelCity(document.getElementById("city"),e);
});

// 修改默认地址
function adsStatus(id) {
    $.post('{{ url('adsStatus') }}',{
        "_token" : '{{ csrf_token() }}',
        "id" : id
    },function (ret) {
        var obj = $.parseJSON(ret);
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

// 删除地址
function adsDel(id) {
    $.post('{{ url('user/set/ads/del') }}',{
        "_token" : '{{ csrf_token() }}',
        "id" : id
    },function (ret) {
        var obj = $.parseJSON(ret);
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

// 设置用户地址
function editUserAds() {
    var city = $('#city').val();
    var area = $('#ads').val();
    if (city == '' || area == '') {
        layer.open({
            content: '省市区和详细地址必须填写!'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    } else {
        $.post('{{ url('user/set/ads') }}', {
            "_token" : '{{ csrf_token() }}',
            "city" : city,
            "ads" : area
        }, function(ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                location.reload();
            } else {
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
    }
}

// 添加新地址
function addAds() {
    location.href='{{ url('user/set/ads/add') }}';
}

// 编辑地址
function adsEdit(id) {
    location.href = '{{ url('user/set/ads/edit') }}/'+id
}
</script>
@endsection






































