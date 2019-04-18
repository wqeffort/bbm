@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">商品管理</a> &raquo; 编辑商品
    </div>
    <!--面包屑导航 结束-->

    <!--TAB切换面板和外置按钮组 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>快捷操作</h3>
            <div class="mark">
                <p>空余扩展栏</p>
            </div>
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="#"><i class="fa fa-list-alt"></i>商品列表</a>
                <a href="#"><i class="fa fa-list-ul"></i>分类列表</a>
                <a href="#"><i class="fa fa-outdent"></i>品牌列表</a>
                <a href="#"><i class="fa fa-tags"></i>标签列表</a>
            </div>
        </div>
    </div>

    <div class="result_wrap">
        <ul class="tab_title">
            <li class="active">基本信息</li>
            <li>商品图集</li>
            <li>详细描述</li>
            <li>售后服务</li>
            <li>工作日志</li>
        </ul>
        <div class="tab_content">
            <table class="add_tab">
                <tr>
                    <th>商品名称：</th>
                    <td>
                        <input type="text" class="lg" name="goods_name" value="{{ $goods->goods_name }}" required="required">
                        <p>商品的名称不能为空！</p>
                    </td>
                </tr>
                <tr>
                    <th>商品描述：</th>
                    <td>
                        <input type="text" class="lg" name="goods_title" value="{{ $goods->goods_title }}" required="required">
                        <p>商品的描述不能为空！字符长度请控制在20-100间</p>
                    </td>
                </tr>
                <tr>
                    <th>商品价格：</th>
                    <td>
                        <input type="number" class="md" name="goods_price" value="{{ $goods->goods_price }}"  required="required">
                        <p>商品的价格必须大于1</p>
                    </td>
                </tr>
                <tr>
                    <th>积分价格：</th>
                    <td>
                        <input type="number" class="md" name="goods_point" value="{{ $goods->goods_point }}"  required="required">
                        <p>商品的积分价格必须大于1</p>
                    </td>
                </tr>
                <tr>
                    <th>所属分类：</th>
                    <td>
                            <select id="category_id" >
                                <option value="{{ $goods->category_id }}">==保持不变==</option>
                            @foreach ($category as $element)
                                <option value="{{ $element->id }}">{{ $element->category_name }}</option>
                            @endforeach 
                            </select>
                    </td>
                        <p>所属的分类不能为空，如果分类不存在请到分类管理中进行添加</p>
                </tr>
                <tr>
                    <th>所属品牌：</th>
                    <td>
                        <select id="brand_id"  required="required">
                            <option value="{{ $goods->brand_id }}">==保持不变==</option>
                            @foreach ($brand as $element)
                                <option value="{{ $element->id }}">{{ $element->brand_name }}</option>
                            @endforeach
                        </select>
                        <p>所属的品牌不能为空，如果品牌不存在请到品牌管理中进行添加</p>
                    </td>
                </tr>
                {{-- <tr>
                    <th>所属标签：</th>
                    <td>
                        <select id="tag_id">
                            @foreach ($tag as $element)
                                <option value="{{ $element['id'] }}">{{ $element['tag_name'] }}</option>
                            @endforeach
                        </select>
                        <p>标签为选填，可以不选</p>
                    </td>
                </tr> --}}
                <tr>
                    <th>搜索关键词：</th>
                    <td>
                        <input type="text" class="lg" name="goods_search" value="{{ $goods->goods_search }}" required="required">
                        <p>为了配合搜索引擎抓取,以及SEO优化，搜索的关键词不能为空，关键词之间请用“|”进行分隔；示例：女士|精美|超值|夏天必备</p>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>商品缩略图：</th>
                    <td>
                        <script type="text/javascript">
                            $(function() {
                                $('#file_upload').uploadify({
                                    'buttonText' : '上 传 图 片',
                                    'formData'     : {
                                        '_token'     : '{{ csrf_token() }}'
                                    },
                                    'swf'      : '{{ asset('class/uploadify/uploadify.swf') }}',
                                    'uploader' : '{{ url('img') }}',
                                    'onUploadSuccess' : function(file, data, response) {
                                        $('input[name=art_thumb]').val(data);
                                        $('#art_thumb_img').attr('src','/'+data);
                                    }
                                });
                            });
                        </script>
                            <input type="text" class="lg" name="art_thumb" value="{{ $goods->goods_pic }}">
                            <input id="file_upload" name="file_upload" type="file" multiple="true">
                        <p style="color:#C40000;">此幅图片为商品主图，必须为正方形（大小不能超过100kb，请注意压缩）必须为白底。</p>
                    </td>
                </tr>
                <tr>
                    <th>缩略图</th>
                    <td>
                        <img src="{{ url($goods->goods_pic) }}" alt="" id="art_thumb_img" style="width: 200px;height: 200px">
                    </td>
                </tr>
            </table>


        </div>
        <div class="tab_content">
            <div class="goods_photo" style="display: flex;justify-content: space-between;">
                <input type="file" name="file" class="file" id="fileField"  />
                <input type="hidden" name="hash" id="hash" value="xoxo"/>
                @foreach (explode("|", $goods->goods_gallery) as $key => $element)
                    <img class="remove{{ $key }}" style="width: 20%;height: 10rem;" onclick="remove('remove{{ $key }}')" src="{{ url($element) }}">
                    <input type="hidden" name="photo" class="remove{{ $key }}" value="{{ $element }}">
                @endforeach
            </div>
            <p style="color: #C40000;">由于时间原因,商品相册暂不提供修改相册功能</p>
        </div>
        <div class="tab_content">
            <script id="editor" name="goods_desc" type="text/plain" style="width:1000px;height:500px;">{!! $goods->goods_desc !!}</script>
            <script type="text/javascript">
                var ue = UE.getEditor('editor');
            </script>
                <style>
                    .edui-default{line-height: 28px;}
                    div.edui-combox-body,div.edui-button-body,div.edui-splitbutton-body
                    {overflow: hidden; height:20px;}
                    div.edui-box{overflow: hidden; height:22px;}
                </style>
        </div>
        <div class="tab_content">
            <script id="editor1" name="goods_service" type="text/plain" style="width:1000px;height:500px;">{!! $goods->goods_server !!}</script>
            <script type="text/javascript">
                var ue = UE.getEditor('editor1');
            </script>
        </div>
        <div class="tab_content">
            <table class="add_tab">
                <tr>
                    <th>工作日志：</th>
                    <td>
                        <textarea class="lg" id="log">{{ $goods->log }}</textarea>
                        <p>用于工作记录交接,限制长度200中文字符</p>
                    </td>
                </tr>
                {{-- <tr class="goods_attr" style="display:none;background:#FCF8E3;">
                    <th width="120">组合价格</th>
                    <td>
                        <table class="inner_list">
                            <tr>
                                <th>颜色</th>
                                <th>尺寸</th>
                                <th>货号</th>
                                <th>价格</th>
                            </tr>
                            <tr>
                                <td>红</td>
                                <td>X</td>
                                <td><input type="text" name="attr_sn[]"></td>
                                <td><input type="text" name="attr_price[]"></td>
                            </tr>
                        </table>
                    </td>
                </tr> --}}

            </table>
        </div>
        <div class="btn_group">
            <input type="submit" onclick="addGoods()" value="提交">
            <input type="button" class="back" onclick="history.go(-1)" value="返回" >
        </div>

        <div class="tips">
            <h3><b>请务必仔细看完</b></h3>
            <p>1-请填写完整的商品信息,缺一不可.</p>
            <p>2-填写信息的同时,请按照填写要求填写</p>
            <h3>商品图集上传问题</h3>
            <p>1-上传商品图片请确保图片为正方形.图片大小请保持在60kb左右,最多不能超过100kb</p>
            <p style="color: #C40000;">2-如上传文件时点击浏览文件无反应的情况,请自己百度搜索"XXX浏览器(你目前使用的浏览器)开启flash."</p>
            <h4>价格换算</h4>
            <p>1-商品换算价格(积分价格)等于{ (商品价格(or 积分) + 属性价格(or 积分)) * 数量 }</p>
        </div>
    </div>
    <!--TAB切换面板和外置按钮组 结束-->
    <script type="text/javascript">
        // 相册删除方法
        // function remove(key) {
        //     layer.confirm('你确定要删除此照片吗?', {
        //         btn: ['确定','取消'] //按钮
        //     }, function(){
        //         $('.'+key).remove();
        //         layer.msg('的确很重要');
        //     }, function(){
        //         layer.msg('下次点击的时候请注意', {
        //             time: 20000, //20s后自动关闭
        //             btn: ['明白了', '知道了']
        //         });
        //     });
        // }
        $(function() {
           $("#fileField").uploadify({
               'height'        : 30,
                'swf'       : '{{ asset('class/uploadify/uploadify.swf') }}',
               'uploader'      :'{{ url('img') }}',
               'width'         : 120,
               'onUploadSuccess' : function(file, data, response) {
                    $("#divPreview").append($("<img style='max-width: 10rem;height: 10rem;' src='/" + data + "'/>"));
                    $("#divPreview").append($("<input type='hidden' name='photo' value='" + data + "'/>"));
                },
               'buttonText'    : '浏览文件',
               'uploadLimit'   : 5,//上传最多图片张数
               'removeTimeout' : 1,
               'preventCaching': true,                                                           //不允许缓存
               'fileSizeLimit' : 4100,                                                              //文件最大
               'formData'      : {
                    '_token'     : '{{ csrf_token() }}'
               }           //hash
           });
           $("#SWFUpload_0").css({                  //设置按钮样式，根据插件文档进行修改
                                'position' :'absolute',
                                'top': 20,
                                'left': 35,
                                'z-index'  : 1
                            });
        });

        function addGoods() {
            var goods_gallery = [];
            $("input[name='photo']").each(function(index){
                goods_gallery[index] = $(this).val();
            });

            var goods_name = $("input[name=goods_name]").val();
            var goods_title = $("input[name=goods_title]").val();
            var goods_price = $("input[name=goods_price]").val();
            var goods_point = $("input[name=goods_point]").val();
            var goods_search = $("input[name=goods_search]").val();
            var goods_pic = $("input[name=art_thumb]").val();
            var category_id = $('#category_id option:selected').val();
            var brand_id = $('#brand_id option:selected').val();
            var log = $("#log").val();
            // var tag_id = $('#tag_id option:selected').val()
            // console.log(cate_id)
            // console.log(attrValue)
            var goods_desc = [];
            goods_desc.push(UE.getEditor('editor').getContent())
            goods_desc.push(UE.getEditor('editor1').getContent())
            if (goods_name == '') {
                layer.msg('请填写商品名称',function () {
                });
            }else if (goods_title == '') {
                layer.msg('请填写商品简单描述',function () {
                });
            }else if (goods_price == '') {
                layer.msg('请填写商品基础售价',function () {
                });
            }else if (goods_point == '') {
                layer.msg('请填写商品基础积分价',function () {
                });
            }else if (goods_search == '') {
                layer.msg('请填写商品搜索关键词',function () {
                });
            }else if (goods_pic == '') {
                layer.msg('请上传商品主图',function () {
                });
            }else if (category_id == '') {
                layer.msg('请选择所属商品分类',function () {
                });
            }else if (brand_id == '') {
                layer.msg('请选择商品所属品牌',function () {
                });
            }else if (goods_gallery == '') {
                layer.msg('商品相册最少请上传一幅照片',function () {
                });
            }else{
                $.post('{{ url('/admin/goods/edit') }}/{{ $goods->id }}', {
                    '_token': '{{ csrf_token() }}',
                    'goods_gallery': goods_gallery,
                    'goods_name': goods_name,
                    'goods_title': goods_title,
                    'goods_price': goods_price,
                    'goods_point': goods_point,
                    'goods_search': goods_search,
                    'goods_pic': goods_pic,
                    'category_id': category_id,
                    'brand_id': brand_id,
                    'goods_desc': goods_desc,
                    'log': log
                }, function (ret) {
                    var obj = JSON.parse(ret);
                    if (obj.status == 'success') {
                        layer.msg(obj.msg);
                        setTimeout(function () {
                            history.back(-1);
                        },1500);
                    }else{
                        layer.msg(obj.msg, function(){
                        });
                    }
                });
            }
        }
    </script>
@endsection










































