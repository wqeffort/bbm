@extends('lib.home.header')
@section('body')
<div class="search_box" style="background: #666;position: relative;
">
    <div class="search">
        <input style="background: #FFF" type="text" name="search" placeholder="请输入商品名称或者关键字进行搜索！" onchange="search()"><i class="fa fa-search" aria-hidden="true"></i>
        <span>搜索</span>
    </div>
</div>
<div class="goodsListBox">
    <div class="listBox" style="padding: .5rem">
        @if ($goods->isNotEmpty())
            @foreach ($goods as $element)
                <a href="{{ url('goods') }}/{{ $element->id }}">
                    <ul style=" display: flex;justify-content: left;overflow: hidden;border-bottom: 1px solid #EEE;padding: .5rem 0;color: #333;">
                        <li><img style="width: 10rem;" src="{{ url($element->goods_pic) }}" alt="{{ $element->goods_name }}"></li>
                        <li style="margin-left: 1rem;display: flex;flex-direction: column;justify-content: space-between;">
                            <h3 style="white-space: nowrap;overflow: hidden;">{{ $element->goods_name }}</h3>
                            <span style="color:#666;white-space: nowrap;overflow: hidden;">{{ $element->goods_title }}</span>
                            <p><b>{{ $element->goods_point }}</b> 积分</p>
                        </li>
                    </ul>
                </a>
            @endforeach
        @endif
    </div>
</div>
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
function search() {
    var data = $('input[name=search]').val();
    location.href = '{{ url('goods/list') }}/search/'+data;
}
</script>


@endsection






































