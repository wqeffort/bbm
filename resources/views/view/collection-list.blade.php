@extends('lib.view.header')
@section('body')
<style>
    .fund {
        position: fixed;
        z-index: 999;
        width: 2rem;
        height: 2rem;
        background: #6395ed;
        opacity: .8;
        text-align: center;
        line-height: 2rem;
        bottom: 8rem;
        left: 0;
    }
    .fund a {
        color: #FFF;
        font-size: 1.5rem;
    }
</style>
<div class="search_box" style="background: #6495ed;position: relative;
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
                <a href="{{ url('vew/goods') }}/{{ $element->id }}">
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
function search() {
    var data = $('input[name=search]').val();
    location.href = '{{ url('view/goods/list') }}/search/'+data;
}
</script>


@endsection






































