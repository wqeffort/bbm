@extends('lib.service.header')
@section('body')
    <style>
    .layui-layer-demo .layui-layer-title {
        border: none;
        background-color: #009688;
        color: #fff;
    }
    </style>
    <div class="weadmin-nav">
        <a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;z-index: 999;" href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i>
        </a>
    </div>
    <div class="weadmin-body">
        <form class="layui-form">
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="we-red">*</span>绑定用户
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="username" name="search" required="" lay-verify="required"
                  autocomplete="off" class="layui-input">
              </div>
              <a class="layui-btn" href="javascript:;" onclick="searchUser()">
                  <i class="fa fa-search"></i> 搜 索
              </a>
          </div>
          <div class="layui-form-item">
              <label for="phone" class="layui-form-label">
                  <span class="we-red">*</span>用户手机
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="phone" name="phone" required="" lay-verify="phone"
                  autocomplete="off" class="layui-input" disabled="disabled">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="we-red">*</span> 将会成为您唯一的登入用户名
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_email" class="layui-form-label">
                  <span class="we-red">*</span>用户姓名
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_email" name="name" required=""                  autocomplete="off" class="layui-input" disabled="disabled">
                  <input type="hidden" value="" name="uuid">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="we-red">*</span>
              </div>
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label"><span class="we-red">*</span>管理权限</label>
              <div class="layui-input-block">
                <input type="radio" name="rank" value="1" lay-skin="primary" title="一般管理员" checked="">
                <input type="radio" name="rank" value="9" lay-skin="primary" title="超级管理员">
              </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="we-red">*</span>所属部门</label>
                <div class="layui-input-block">
                    <select id="cate" lay-verify="">
                        <option value="">请选择所属的部门</option>
                        <option value="8">梦 享 家</option>
                        <option value="5">融通四海</option>
                        <option value="1">凤天呈祥</option>
                        <option value="2">梦味以求</option>
                        <option value="3">金屋良缘</option>
                        <option value="4">愿走高飞</option>
                        <option value="9">财务部门</option>
                    </select>
                </div>
            </div>
          <div class="layui-form-item">
              <label for="L_pass" class="layui-form-label">
                  <span class="we-red">*</span>密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_pass" name="pass" required="" lay-verify="pass"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  6到16个字符
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
                  <span class="we-red">*</span>确认密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_repass" name="repass" required="" lay-verify="repass"
                  autocomplete="off" class="layui-input">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label"></label>
              <a href="javascript:;" onclick="sub()" class="layui-btn" lay-filter="add" lay-submit="">提 交</a>
          </div>
      </form>
    </div>

    <script type="text/javascript">
var layerList = '';
    	function searchUser() {
            var text = $('input[name=search]').val();
            loading();
            $.post('{{ url('service/search/user') }}', {
                "_token" : '{{ csrf_token() }}',
                "text" : text
            }, function(ret) {
                var obj = $.parseJSON(ret);
                layer.close(loadingBox)
                if (obj.status == 'success') {
                    if (obj.data.length > 0) {
                        var html = '';
                        $.each(obj.data, function(index, val) {
                            var img = '';
                            if (val.user_pic) {
                                img = val.user_pic;
                            }else{
                            var  img = '{{ url('images/c-pic.png') }}'
                            }
                            html += '<li id="info'+index+'" style="display: flex;justify-content: space-between;padding: .5rem;border-bottom: 1px solid #EEE;margin-top: .5rem;line-height: 2rem;">\
                                <img style="width: 2.2rem;height: 2.2rem;line-height: 1.5rem;border-radius: 50%;" src="'+img+'" alt="">\
                                <p>'+val.user_name+'</p>\
                                <span>'+val.user_phone+'</span>\
                                <b style="display:none;">'+val.user_uuid+'</b>\
                                <a href="javascript:;" onclick="info('+index+')" class="layui-btn"> Checked </a>\
                                </li>'
                        });
                        layerList = layer.open({
                            type: 1,
                            skin: 'layui-layer-demo', //样式类名
                            title: '搜索结果 共计('+obj.data.length+')个',
                            closeBtn: 0, //不显示关闭按钮
                            anim: 2,
                            shadeClose: true, //开启遮罩关闭
                            area: ['30rem','60%'],
                            content: '<ul>'+html+'</ul>'
                        });
                    }
                }else{
                    layer.msg(obj.msg, {icon: 2});
                }
            });
        }

        function info(id) {
            var tag = '#info'+id;
            var name = $(tag).children('p').html();
            var phone = $(tag).children('span').html();
            var uuid = $(tag).children('b').html();
            // var img = $(tag).children('img').attr('src');
            // console.log(name);
            // console.log(phone);
            // console.log(img);
            $('input[name=name]').val(name);
            $('input[name=phone]').val(phone);
            $('input[name=uuid]').val(uuid);
            layer.close(layerList);
        }

        function sub() {
            var uuid = $('input[name=uuid]').val();
            var pass = $('input[name=pass]').val();
            var repass = $('input[name=repass]').val();
            var rank = $('input[name=rank]:checked').val();
            var cate = $('#cate option:selected').val();
            return;
            if (uuid && pass == repass) {
                loading();
                $.post('{{ url('service/admin/add') }}', {
                    "_token" : '{{ csrf_token() }}',
                    "password" : pass,
                    "uuid" : uuid,
                    "rank" : rank,
                    "cate" : cate
                }, function(ret) {
                    var obj = $.parseJSON(ret);
                    layer.close(loadingBox);
                    result(obj.status,obj.msg);
                });
            }else{
                layer.msg('请先选择用户', {icon: 2});
            }
        }
    </script>
@endsection