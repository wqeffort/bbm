@extends('lib.view.header')
@section('body')
<style type="text/css">
    .aui-flexView {
    width: 100%;
    height: 100%;
    margin: 0 auto;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
}

.aui-scrollView {
    width: 100%;
    height: 100%;
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    -ms-flex: 1;
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    position: relative;
    margin-top: 0;
}

.aui-navBar {
    height: 44px;
    position: relative;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    z-index: 1002;
    background: #f9f9f9;
}

.aui-navBar:after {
    content: '';
    position: absolute;
    z-index: 2;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 1px;
    border-bottom: 1px solid #f9f9f9;
    -webkit-transform: scaleY(0.5);
    transform: scaleY(0.5);
    -webkit-transform-origin: 0 100%;
    transform-origin: 0 100%;
}

.aui-navBar-item {
    height: 44px;
    min-width: 25%;
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 25%;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    padding: 0 0.9rem;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    font-size: 0.7rem;
    white-space: nowrap;
    overflow: hidden;
    color: #a0a0a0;
    position: relative;
}

.aui-navBar-item:first-child {
    -webkit-box-ordinal-group: 2;
    -webkit-order: 1;
    -ms-flex-order: 1;
    order: 1;
    margin-right: -25%;
    font-size: 0.9rem;
    font-weight: bold;
}

.aui-navBar-item:last-child {
    -webkit-box-ordinal-group: 4;
    -webkit-order: 3;
    -ms-flex-order: 3;
    order: 3;
    -webkit-box-pack: end;
    -webkit-justify-content: flex-end;
    -ms-flex-pack: end;
    justify-content: flex-end;
}

.aui-center {
    -webkit-box-ordinal-group: 3;
    -webkit-order: 2;
    -ms-flex-order: 2;
    order: 2;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    height: 44px;
    width: 60%;
    margin-left: 25%;
}

.aui-center-title {
    text-align: left;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    display: block;
    text-overflow: ellipsis;
    font-size: 0.95rem;
    color: #444444;
}

.icon {
    width: 20px;
    height: 20px;
    display: block;
    border: none;
    float: left;
    background-size: 20px;
    background-repeat: no-repeat;
}

.icon-return {
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAD4ElEQVRoQ+3aP2hbRxwH8O/vPcUhyA6CQIZQAh7SIUMXDa6HEPnd6QUNHTp4aaFD6NJO6VzaJqR7kiGZQjsEsmQISQii0p3ySlPcIV1KHSgdCoXW0FIoBTlEkd6vnPEz+nN2EusZn856my2kd5/3u/fu7nuPcMAOOmBeTMG+V3xa4WmFPbsC0y7tWUFHOHtS4TiOi51OZz5Jkp9du4C5g6WUHwC4AaAI4Pt2u11dWVl55go8V7CU8qNN7JaPmS9ora95B46i6NMgCL60wD5TStn+vy/XIJcKSymvALgwLGDmF8w832q1/tgXneWk44JJSnkdgOnKA4fBEtGyUuqeK1jTjnHAJIS4RUTvW7DPgiB4p9lsapewuwZXKpVCoVC4DWDZAvoPwDml1A+uYXcFLpfLh0ql0j0iqlkq+w+ASGv9k4vY1wYvLi4eKRaLDwAIC+hPIqo0m81fXcW+FtjMntI0VQDetoB+A1BRSv3uMvaVwQsLC0fn5uZaAMqWbvw0DMOlRqPxl+vYVwILIY4BaBHRWxbQj91uVyZJ8u8kYF8KjuP4eJqmjwGcslT2uzAMa41Goz0p2B3BUsqTABIA8xZsfWZm5t16vf58krDbguM4nt+s7AkL6E63230vSZLupGGtYCHEaSJ6BOC4pbJfaa0/BMCTiB0BCyHMg+lbIipZQFeVUp9MKjRr99ZcWkppxtdvABy1oJxa4o1z0TfAQogzBktER/p/jJmZiK4CuD/OSfb7u8zcW19ff2KSF6pWq2eZ2VT28BB240+icRZU+00dOP+6WcaaJd4vRPTmMNYjaD9tzSzgTRoxMPwws0+V7Qe3TYU/JiKTWgwcBu1Zl0aaplc2blAppcmjTC41giaihwCeOHU37qIxzPy31vp6/7B0nplvkuXmZebPtdaXd3Ee574y8AiOomiZiG4TUcH7iUcGjKKoFgTB3eFhynzOzH5NLTO0mYgQUX1zu2S42H4tHjLd0tJSOQxDk3KMTDWZ2a/lYV+lzWLCpB0m9Rh+gvsVAGS6arV6iplNEGBbG/sV8WTol6QffoV4GdrkW71e7xERnbYMWX7FtBmwUqmUCoWCyaZH4loAfgXxfZUu9nq9OhGZNfTwg8yvrZZMV6vVDnc6nbu2/SUAfm2m9XXvbXcQmdmv7dK+frzTHrF3G+KZe6e3AEx2XXZp+zS3wEpKeRHAF5Yh65JSynzmxJEb2Gi2CRLOK6W+dkI75jseVsPQi2mP19bWxOrqasdbsIGZ/eTZ2dk3tNZPXYFuPXBca9BetyfXe3ivG5vH70/BeVxFl39jWmGXq5NH2w5chf8HRY56PDMklQ8AAAAASUVORK5CYII=');
}

.icon-more {
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAADzElEQVRoQ+2bT2gdRRzHf9/ZBzHQBE1BSvXkHxAE9aBgSYqbnUlOFhVJpWDpzdaDFgRPiqVab4J/EA/20lapaBBF8ODLzO7Dg0IkiOJNwUNBESRP1B6q2fnJtqmU8pIddt4MC9m97ndmPp/5vheyfx5ohx3YYb7UCYdsfG5u7qaJiYlDRLQPwLS1drXX653v9/s/h1z32rmjNayUepCIviCi6WsB+MrxbJ7nb8eQjiIspdxfyQKY3Ebqea31a6GlowgrpX4kojvqZADcsrKy8ktdzud8cOHFxcWbrbW/uUAy82FjzPsu2aaZ4MJZlh0SQpx3AWTmd40xR12yTTPBhavvL4AvXQCZ+YQx5mWXbNNMcOE0TXtJkvwF4IY6SGvtQ3meO21O3VxbnQ8uXC2slCqIKK2BvLSxsbFrMBhsNJVxGRdFeH5+/m4hhAawZwuof6y1T+Z5vuwC7ZOJIlwBpml6a5IkHwHYdx3wBWZ+whjztY+I69howleB0jS9K0mS2epfy7IsV4ui+IqI2BXYNxdd2BfYd3wn7LuDbR/fNdz2hnz5uoZ9d7Dt47uGIzSELMtuE0JMD4fDH9bW1v6NsOb/S0RtWEr5MBG9AeD2TYI/mfnkzMzMm8vLy2UM8WjCSqmnieidUVLM/ElZlgdDXylVa0cRzrLsBSHEqZoGPx8Oh4+F/ogHF07T9MZerzd0+bhaaw+GvkQMLqyUqi78qxsAtQczv2KMeak26BEILiylPALgjCPjOa31Ecdso1gM4XsAfOdCx8zHjTFvuWSbZoILV2BSyt8B7K6DZOZ7jTHf1+V8zscSfhTAx0QktoE9rbV+ykfGZWwU4Qoky7LDAM4CGLXme5vf3eC3eqIJb0pnQohjzHz5cSkzrwL4QGtd/VGzLg35ZqIK+8KOY3wnPI5dbPMcXcNtbmccbF3D49jFNs/RNdzmdsbBFr3hHfP0UEq5G8CnRDR3XVPV8+HHjTHfjKPBujmiNLywsLCXmQdEdOcoIGb+G8ABrXWVCXpEEZZSngFQdyfjV6313qC2Me5aLi0tJevr60MAU3UyZVneXxTFWl3O53zwhqWUCwD6jpCvaq1fdMw2igUXzrLsgBDiMxc6a+3reZ4/55JtmgkuPDs7OzU5OflHze2dy/xlWT5SFIXT5rRWuAJTSn1LRPdtB1m9NJ0kyVS/37/YVMZlXPCGKwgp5QNElAPYtRWUtfaZGC+JRxG+Kg1Aj3ojHsBRrfVpl4Z8M9GEK9BRv3mw1n44GAx+8hVxHR9V2BUqZK4TDrm7bZh7xzX8HwiOM0xOIScDAAAAAElFTkSuQmCC');
}

.m-scrolltab {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}

.scrolltab-nav {
    height: 100%;
    background-color: #ffffff;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    position: relative;
    z-index: 1;
}

.scrolltab-item {
    height: 3rem;
    position: relative;
    z-index: 1;
    width: 90px;
    display: block;
    line-height: 3rem;
    text-align: center;
}

.scrolltab-item:before {
    content: '';
    position: absolute;
    z-index: 0;
    top: 0;
    right: 0;
    height: 100%;
    border-right: 1px solid #ffffff;
    -webkit-transform: scaleX(0.5);
    transform: scaleX(0.5);
    -webkit-transform-origin: 100% 0;
    transform-origin: 100% 0;
}

.scrolltab-item.crt:before {
    content: '';
    position: absolute;
    z-index: 0;
    top: 0;
    right: 0;
    height: 100%;
    border-right: 1px solid #f2f2f2;
    -webkit-transform: scaleX(0.5);
    transform: scaleX(0.5);
    -webkit-transform-origin: 100% 0;
    transform-origin: 100% 0;
}

.scrolltab-title {
    font-size: 0.8rem;
    color: #666;
    overflow-x: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.scrolltab-content {
    height: 100%;
    background-color: #f2f2f2;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    -ms-flex: 1;
    flex: 1;
    padding: 0.4rem 0.8rem 0.8rem 0.8rem;
    position: relative;
}

.scrolltab-item.crt {
    background-color: #f2f2f2;
    position: relative;
}

.scrolltab-item.crt .scrolltab-title {
    color: #fe7923;
}

.scrolltab-item.crt:after {
    content: '';
    position: absolute;
    z-index: 0;
    top: 0.8rem;
    left: 0;
    width: 0.2rem;
    height: 1.5rem;
    background-image: -webkit-gradient(linear,bottom top,right top,from(#f5dcbb),to(#fe7923));
    background-image: -webkit-linear-gradient(bottom,#f5dcbb,#fe7923);
    background-image: -moz-linear-gradient(bottom,#f5dcbb,#fe7923);
    background-image: linear-gradient(to bottom,#f5dcbb,#fe7923);
    background-color: #fe7923;
    border-radius: 0.5rem;
}

.aui-ad-head a {
    width: 30%;
    display: block;
    float: left;
    margin-right: 5%;
}

.aui-ad-head a img {
    width: 100%;
    display: block;
    border-radius: 5px;
}

.aui-ad-head {
    padding-bottom: 15px;
}

.scrolltab-content-item h2 {
    color: #4b4b4b;
    font-size: 14px;
    font-weight: normal;
    padding-bottom: 15px;
    padding-top: 10px;
}

.aui-flex-box {
    padding: 1px;
    display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    align-items: center;
}

.aui-flex-box-bd {
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    flex: 1;
    min-width: 0;
    font-size: 0.9rem;
    color: #444;
}

.aui-flex-box-fr {
    text-align: right;
    color: #ff8c01;
    padding-right: 15px;
    position: relative;
    font-size: 0.8rem;
}

.aui-flex-box-fr:after {
    content: " ";
    display: inline-block;
    height: 7px;
    width: 7px;
    border-width: 2px 2px 0 0;
    border-color: #ff8c01;
    border-style: solid;
    -webkit-transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
    transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
    position: relative;
    top: -2px;
    position: absolute;
    top: 50%;
    margin-top: -4px;
    right: 2px;
    border-radius: 1px;
}

.aui-flex-links {
    padding: 10px 8px;
    position: relative;
    overflow: hidden;
    background: #fff;
    border-radius: 5px;
    margin-top: 15px;
    margin-bottom: 15px;
}

.aui-flex-links-item {
    position: relative;
    float: left;
    padding: 5px 0;
    width: 27.333333%;
    box-sizing: border-box;
    margin: 0 3%;
}

.aui-flex-links-img {
    width: 100%;
    height: 100%;
    margin: 0 auto;
}

.aui-flex-links-img img {
    width: 100%;
    height: auto;
    display: block;
    border: none;
}

.aui-flex-links-text {
    color: #444;
    font-size: 12px;
    text-align: center;
    padding-top: 5px;
}

.aui-class-ad-img a img {
    width: 100%;
    height: auto;
    display: block;
    border: none;
    border-radius: 4px;
}
</style>
<script type="text/javascript" src="{{ url('js/scrollTab.js') }}"></script>
<section class="aui-flexView">
    <header class="aui-navBar aui-navBar-fixed">
        <a href="javascript:;" class="aui-navBar-item" style="min-width: 15%; margin-right: -36%;">
            <i class="icon icon-return"></i>
        </a>
        <div class="aui-center">
            <span class="aui-center-title">商品分类</span>
        </div>
        <a href="javascript:;" class="aui-navBar-item">
            <i class="icon icon-more"></i>
        </a>
    </header>
    <section class="aui-scrollView">
        <div class="m-scrolltab" data-ydui-scrolltab>
            <div class="scrolltab-nav">
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">为你推荐</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">女装</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">食品</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">手机数码</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">母婴</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">运动户外</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">美妆洗护</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">生鲜</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">男装</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">鲜花宠物</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">百货</div>
                </a>
                <a href="javascript:;" class="scrolltab-item">
                    <div class="scrolltab-title">内衣配置</div>
                </a>
            </div>
                    <div class="scrolltab-content">
                        <div class="scrolltab-content-item">
                            <h2>排行榜</h2>
                            <div>
                                <div class="aui-ad-head clearfix">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/ad-head-001.png" alt="">
                                    </a>
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/ad-head-002.png" alt="">
                                    </a>
                                    <a href="javascript:;" style="margin-right:0">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/ad-head-003.png" alt="">
                                    </a>
                                </div>
                                <a class="aui-flex-box" href="javascript:;">
                                    <div class="aui-flex-box-bd">实时分类推荐</div>
                                    <div class="aui-flex-box-fr">热销榜</div>
                                </a>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-001.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">沐浴露</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-002.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">鼠标</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-003.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">洗面奶</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-004.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">手机</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-005.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">耳机</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-006.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">车棚</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-007.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">运动鞋</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-008.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">防晒霜</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-009.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">洗发水</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>女装</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>食品</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-002.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>手机数码</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>母婴</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>运动户外</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>美妆洗护</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>生鲜</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>男装</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>鲜花宠物</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>百货</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="scrolltab-content-item">
                            <h2>内衣配置</h2>
                            <div>
                                <div class="aui-class-ad-img">
                                    <a href="javascript:;">
                                        <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/class-ad-001.jpg" alt="">
                                    </a>
                                </div>
                                <div class="aui-flex-links">
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-010.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">早春新品</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-011.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">连衣裙</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-012.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">衬衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-013.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">T恤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-014.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔裤</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-015.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">卫衣</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-016.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">针织衫</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-017.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">牛仔外套</div>
                                    </a>
                                    <a href="javascript:;" class="aui-flex-links-item">
                                        <div class="aui-flex-links-img">
                                            <img src="http://www.17sucai.com/preview/1268063/2018-08-15/class/images/pd-018.jpg" alt="">
                                        </div>
                                        <div class="aui-flex-links-text">自营女装</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
@endsection
