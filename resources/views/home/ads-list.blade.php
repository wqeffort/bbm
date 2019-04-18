@extends('lib.home.header')
@section('body')
<ol style="width: 100%; background: #C40000;color: #FFF;height: 2rem;line-height: 2rem;">
    <a style="color:#FFF;padding: .5rem 1rem" href="{{ url('car') }}">< 返回购物车结算</a>
</ol>
<script type="text/javascript" src="{{ asset('js/Popt.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/city.json.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/citySet.js') }}"></script>
    <dl class="adsList">
        @foreach ($ads as $element)
        <div class="blank"></div>
            <dd>
                <ul>
                    <li>{{ $element->name }}</li>
                    <li>{{ $element->phone }}</li>
                </ul>
                <p>{{ $element->province.$element->city.$element->area.$element->ads }}</p>
                <ul>
                    <li onclick="adsStatus({{ $element->id }})" style="font-size: .8rem; color: #999">
                        @if ($element->status == 1)
                            <i style="color:#C40000;" class="fa fa-circle"></i> 默认地址
                        @else
                            <i style="color:#C40000;" class="fa fa-circle-o"></i> 设为默认
                        @endif
                    </li>
                    {{-- <li style="font-size: .8rem"><i class="fa fa-trash-o"></i> 删除地址</li> --}}
                </ul>
            </dd>
            <div class="blank"></div>
        @endforeach
    </dl>
<div class="addAdsBtn">
        <a href="{{ url('adsAdd') }}">
            <ul>
                <li>添加收货地址</li>
                <li>+</li>
            </ul>
        </a>
    </div>


<div class="blank" style="background:#FFF;height: 10rem;"></div>

<script type="text/javascript">
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

</script>


@endsection






































