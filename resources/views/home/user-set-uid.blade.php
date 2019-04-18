@extends('lib.home.header')
@section('body')
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('js/iscroll-zoom.js') }}"></script>
    <script src="{{ asset('js/hammer.js') }}"></script>
    <script src="{{ asset('js/jquery.photoClip.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/zzsc.css') }}"/>
<style type="text/css">
    #leftMenuBtn {
        display: none;
    }
  html,body{
    background: #EEE;
    /*height: 100%;*/
    margin: 0;
    padding: 0;
  }
    ul li {
        padding: .5rem 1rem;
        margin-top: .5rem;
        background: #FFF;
    }
    input {
        width: 60%;
        text-align: center;
    }
    .text {
        display: flex;
        justify-content: space-between;
    }
    ol {
        margin: 1rem;
        padding: .2rem .5rem;
        background: #333;
        text-align: center;
        color: #FFF;
    }
    .hint_style{
    padding-bottom: 60px !important;
    font-size: 1rem;
    font-style: normal;
}
.hint_style .contact_mabile {
    padding-left: 4rem;
}
#clipArea11 {
    position: fixed;
    z-index: 99;
    width: 100%;
    height: 20rem;
    top: 0;
    left: 0;
    display: none;
}
#clipBtn11 {
    display: none;
    position: fixed;
    z-index: 999;
    border-radius: 50%;
    color: #FFF;
    width: 3rem;
    height: 3rem;
    background: #C40000;
    top: 0;
    right: 0;
}
#view11 {
      margin: 0 auto;
      height: 88px;
      position: relative;
}
#view11 label {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
}
#clipArea12 {
    position: fixed;
    z-index: 99;
    width: 100%;
    height: 20rem;
    top: 0;
    left: 0;
    display: none;
}
#clipBtn12 {
    display: none;
    position: fixed;
    z-index: 999;
    border-radius: 50%;
    color: #FFF;
    width: 3rem;
    height: 3rem;
    background: #C40000;
    top: 0;
    right: 0;
}
#view12 {
    margin: 0 auto;
    height: 88px;
    position: relative;
}
#view12 label {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
}


.info_photo ul{
    display: flex;
    justify-content: space-around;
}
.mod_info {
    text-align: left;
    font-size: .8rem;
}
.mod_info li {
    text-align: left;
    font-size: .8rem;
    max-width: 12rem;
}
#sub a {
    padding: .5rem 1rem;
    background: #C40000;
    color: #FFF;
    font-size: 1rem;
}
</style>

<ul>
    <li class="text">
        <p>姓名:</p>
        <input name="user_name" value="请先拍照身份证" type="text" style="border:none;" disabled="disabled">
    </li>
    <li class="text">
        <p>性别:</p>
        <input name="user_sex" value="请先拍照身份证" type="text" style="border:none;" disabled="disabled">
    </li>
    <li class="text">
        <p>生日</p>
        <input name="user_birthday" value="请先拍照身份证" type="text" style="border:none;" disabled="disabled">
    </li>
    <li class="text">
        <p>证件类型</p>
        <input name="user_uid_type" value="请先拍照身份证" type="text" style="border:none;" disabled="disabled">
    </li>
    <li class="text">
        <p>证件号码:</p>
        <input name="user_uid" value="请先拍照身份证" type="text" style="border:none;" disabled="disabled">
    </li>
    <li>
        <p>身份证正面:</p>
        @if ($user->user_uid_a)
            <img style="width: 100%;" src="{{ url($user->user_uid_a) }}">
            <input type="hidden" value="{{ $user->user_uid_a }}" name="uid_1">
        @else
            <div class="container11">
                        <div id="clipArea11"></div>
                        <button id="clipBtn11" class="btn" onclick="none11()">截取</button>
                        <div id="view11" style="background-image:url({{ url('images/add.png') }});">
                            <label for="file11"></label >
                            <input type="file" style="display: none;" onchange="block11()" id="file11"/>
                        </div>
                    </div>
                    <input type="hidden" name="uid_1">
        @endif
    </li>
    <li>
        <p>身份证背面:</p>
        @if ($user->user_uid_b)
            <img style="width: 100%;" src="{{ url($user->user_uid_b) }}">
            <input type="hidden" value="{{ $user->user_uid_b }}" name="uid_2">
        @else
            <div class="container12">
                        <div id="clipArea12"></div>
                        <button id="clipBtn12" class="btn" onclick="none12()">截取</button>
                        <div id="view12" style="background-image:url({{ url('images/opp.png') }});">
                            <label for="file12"></label >
                            <input type="file" style="display: none;" onchange="block12()" id="file12"/>
                        </div>
                    </div>
                    <input type="hidden" name="uid_2">
        @endif
    </li>
    <li>
        <div id="sub" style="margin-bottom: 5rem;text-align: center;padding: 3rem;">
            <a href="javascript:;" onclick="ocr()">验证身份信息</a>
        </div>
    </li>
</ul>


<div class="nav-bottom" style="position: fixed;">
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
<script type="text/javascript" src="{{ asset('js/jquery.easing.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/zzsc.js') }}"></script>
<script type="text/javascript">
function block11() {
    $("#clipArea11").css("display","block");
    $("#clipBtn11").css("display","block");
}
function none11() {
    $("#clipArea11").css("display","none");
    $("#clipBtn11").css("display","none");
}
function block12() {
    $("#clipArea12").css("display","block");
    $("#clipBtn12").css("display","block");
}
function none12() {
    $("#clipArea12").css("display","none");
    $("#clipBtn12").css("display","none");
}

$(function() {
    $("#clipArea11").photoClip({
        width: 300,
        height: 180,
        file: "#file11",
        view: "#view11",
        ok: "#clipBtn11",
        loadStart: function() {
            // $(".photo-clip-rotateLayer").html("<img src='images/loading.gif'/>");
            // console.log("照片读取中");
        },
        loadComplete: function() {
            layer.open({
                content: '照片上传成功，请自行编辑（两个手指可进行放大和旋转），完成后点击裁剪即可！'
                ,skin: 'msg'
                ,time: 5 //2秒后自动关闭
            });
        },
        clipFinish: function(dataURL) {
            console.log(dataURL)
            // dataImg=dataURL.replace(/^(data:image\/.*,)/,"");
            $.ajax({
                url: "{{ url('imgBase64') }}",
                data: {
                    "_token":'{{ csrf_token() }}',
                    "str": dataURL
                    // img: dataImg
                },
                type: 'post',
                dataType: 'html',
                success:function(ret) {
                    var obj = JSON.parse(ret);
                    if (obj.status == 'success') {
                        $('input[name=uid_1]').val(obj.msg);
                    }else{
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                }
            })
        }
    });
});

$(function() {
    $("#clipArea12").photoClip({
        width: 300,
        height: 180,
        file: "#file12",
        view: "#view12",
        ok: "#clipBtn12",
        loadStart: function() {
            // $(".photo-clip-rotateLayer").html("<img src='images/loading.gif'/>");
            // console.log("照片读取中");
        },
        loadComplete: function() {
            layer.open({
                content: '照片上传成功，请自行编辑（两个手指可进行放大和旋转），完成后点击裁剪即可！'
                ,skin: 'msg'
                ,time: 5 //2秒后自动关闭
            });
        },
        clipFinish: function(dataURL) {
            // dataImg=dataURL.replace(/^(data:image\/.*,)/,"");
            $.ajax({
                url: "{{ url('imgBase64') }}",
                data: {
                    "_token":'{{ csrf_token() }}',
                    "str": dataURL
                    // img: dataImg
                },
                type: 'post',
                dataType: 'html',
                success:function(ret) {
                    var obj = JSON.parse(ret);
                    if (obj.status == 'success') {
                        $('input[name=uid_2]').val(obj.msg);
                    }else{
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                }
            })
        }
    });
});

// 提交请求验证身份信息
function ocr() {
    load();
    var uimg = $("input[name=uid_1]").val();
    // var uimg = true;
    if (uimg) {
        $.post('{{ url('ocr') }}',{
            "_token" : '{{ csrf_token() }}',
            "uimg" : uimg
        },function (ret) {
            var obj = $.parseJSON(ret);
            var info = $.parseJSON(obj.data);
            layer.close(loadBox);
            if (obj.status == 'success') {
                $('input[name=user_name]').val(info.name);
                $('input[name=user_uid]').val(info.num);
                $('input[name=user_sex]').val(info.sex);
                $('input[name=user_uid_type]').val("身份证");
                $('input[name=user_birthday]').val(info.birth);
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                if (info.success) {
                    $('#sub').html('<a href="javascript:;" style="background:#333;" onclick="sub()">提交身份认证</a>')
                }
            }else{
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
    }else{
        alert('请先上传身份证正反面')
    }
}

function sub() {
    load()
    // 获取到用户的基本资料补充到数据库
    var user_name = $('input[name=user_name]').val();
    var user_uid = $('input[name=user_uid]').val();
    var user_sex = $('input[name=user_sex]').val();
    var user_uid_type = $('input[name=user_uid_type]').val();
    var user_birthday = $('input[name=user_birthday]').val();
    var user_uid_a = $("input[name=uid_1]").val();
    var user_uid_b = $("input[name=uid_2]").val();

    console.log(user_uid_a);
    if (user_name.length == '') {
        layer.open({
            content: '用户姓名识别失败,请重新刷新页面进行上传识别'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (user_uid.length == '') {
        layer.open({
            content: '用户身份证号码识别失败,请重新刷新页面进行上传识别'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (user_uid_a.length == '') {
        layer.open({
            content: '未获取到用户身份证正面图片,请重新刷新页面进行上传识别'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (user_uid.length == '') {
        layer.open({
            content: '未获取到用户身份证反面图片,请重新刷新页面进行上传识别'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        $.post('{{ url('user/set/uid') }}',{
            "_token" : '{{ csrf_token() }}',
            "user_name" : user_name,
            "user_uid" : user_uid,
            "user_sex" : user_sex,
            "user_uid_type" : user_uid_type,
            "user_birthday" : user_birthday,
            "user_uid_a" : user_uid_a,
            "user_uid_b" : user_uid_b
        },function (ret) {
            layer.close(loadBox);
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                location.href = '{{ url('user/set') }}'
            }else{
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                location.href = '{{ url('user/set') }}'
            }
        });
    }
}
</script>
@endsection






































