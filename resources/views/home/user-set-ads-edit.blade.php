@extends('lib.home.header')
@section('body')
<script type="text/javascript" src="{{ asset('js/Popt.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/city.json.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/citySet.js') }}"></script>
    <style type="text/css">
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
    </style>

<dl>
    <dt>修改收货地址</dt>
    <dd>收件人姓名: <input style="border: none;width: 65%;" type="text" name="name" placeholder="请输入联系人姓名" value="{{ $ads->name }}"></dd>
    <dd>联系电话: <input style="border: none;width: 65%;" type="number" name="phone" placeholder="请输入联系人电话"  value="{{ $ads->phone }}"></dd>
    <dd>
        选择省市区:
        <input style="border: none;width: 65%;" class="input" name="city" id="city" type="text" placeholder="点击选择省市区" autocomplete="off" readonly="true" value="{{ $ads->province }}/{{ $ads->city }}/{{ $ads->area }}" /><s></s>
    </dd>
    <dd>
        详细地址:
        <input style="border: none;width: 65%;" placeholder="请输入详细地址" type="text" name="ads" value="{{ $ads->ads }}">
    </dd>
</dl>

<div style="text-align: center;margin: 5rem;">
        <a style="background: #C40000;color: #FFF;padding: .5rem 1rem;" href="javascript:;" onclick="addAds({{ $ads->id }})">确认修改地址</a>
    </div>


<script type="text/javascript">
// // 获取定位收货地址
//     wx.ready(function () {
//         // 获取用户坐标位置
//         wx.getLocation({
//             type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
//             success: function (res) {
//                 lng = res.longitude; // 经度，浮点数，范围为180 ~ -180。
//                 lat = res.latitude; // 纬度，浮点数，范围为90 ~ -90
//                 var speed = res.speed; // 速度，以米/每秒计
//                 var accuracy = res.accuracy; // 位置精度
//                 if (res.errMsg == 'getLocation:ok') {
//                     // 提交定位信息获取当前城市
//                     $.post('{{ url('adsListGetlocation') }}',{
//                         "_token" : '{{ csrf_token() }}',
//                         "lat" : lat,
//                         "lng" : lng,
//                     },function (ret) {
//                         layer.close(loadBox);
//                         var obj = $.parseJSON(ret);
//                         console.log(obj.text);
//                         if (obj.status == 'success') {
//                             // $('input[name=harea]').val(obj.area);
//                             // $('input[name=hproper]').val(obj.city);
//                             // $('input[name=hidden]').val(obj.province);
//                             $('input[name=ads]').val(obj.text);
//                         }else{

//                         }
//                     })
//                 }else{
//                     //提示
//                     layer.open({
//                         content: '请开启手机(GPS)定位'
//                         ,skin: 'msg'
//                         ,time: 2 //2秒后自动关闭
//                     });
//                 }
//             }
//         });
//     });

// 地区加载插件
$("#city").click(function (e) {
    SelCity(this,e);
});
$("s").click(function (e) {
    SelCity(document.getElementById("city"),e);
});

// 提交添加地址的表单
function addAds(id) {
    var name = $('input[name=name]').val();
    var phone = $('input[name=phone]').val();
    var city = $('input[name=city]').val();
    var ads = $('input[name=ads]').val();
    $.post('{{ url('editAds') }}', {
        "_token" : '{{ csrf_token() }}',
        "id" : id,
        "name" : name,
        "phone" : phone,
        "city" : city,
        "ads" : ads
    }, function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            location.href = '{{ url('user/set/ads') }}';
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}

</script>


@endsection






































