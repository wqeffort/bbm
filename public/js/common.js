// 此文件为后台共用JS文件
var loadingBox = '';
var loadBox = '';
// 正则判断身份证号码是否合法
function IsUid(Uid){
	var res=new RegExp(/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/);
	var restu=Uid.match(res);
	if(restu){
		return true;
	}else{
		return false;
	}
}

// 正则判断手机号码是否合法
function isPhone(phone){
    var re = new RegExp(/^((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)$/);
    var retu = phone.match(re);
    if(retu){
        return true;
    }else{
        return false;
    }
}

// 匹配会员等级
function isRank(int) {
    switch (int) {
        case 0:
            rank = '普通用户'
            break;
        case 1:
            rank = '体验会员'
            break;
        case 2:
            rank = '男爵会员'
            break;
        case 3:
            rank = '子爵会员'
            break;
        case 4:
            rank = '伯爵会员'
            break;
        case 5:
            rank = '侯爵会员'
            break;
        case 6:
            rank = '公爵会员'
            break;
        case 9:
            rank = '内部会员'
            break;
        default:
            rank = '未知级别'
            break;
    }
    return rank;
}

function isSex(int) {
    switch (int) {
        case 1:
            rank = '男'
            break;
        case 2:
            rank = '女'
            break;
        default:
            rank = '未知'
            break;
    }
}

// loading动画
function loading() {
	loadingBox = layer.load(1, {
	  	shade: [0.7,'#FFF'] //0.1透明度的白色背景
	});
}

var province = '';
var city = '';
var area = '';
// 地区三级联动
//初始数据
var areaData = '';
var $form;
var form;
var $;
// layui.use(['jquery', 'form'],
// function() {
//     $ = layui.jquery;
//     form = layui.form();
//     $form = $('form');
//     loadProvince();
// });
//加载省数据
// function loadProvince() {
//     var proHtml = '';
//     for (var i = 0; i < areaData.length; i++) {
//         proHtml += '<option value="' + areaData[i].provinceCode + '_' + areaData[i].mallCityList.length + '_' + i + '">' + areaData[i].provinceName + '</option>';
//     }
//     //初始化省数据
//     $form.find('select[name=province]').append(proHtml);
//     form.render();
//     form.on('select(province)',
//     function(data) {
//         $form.find('select[name=area]').html('<option value="">请选择县/区</option>').parent().hide();
//         province = $('select[name=province]').find("option[value="+data.value+"]").text();
//         console.log(province);
//         var value = data.value;
//         var d = value.split('_');
//         var code = d[0];
//         var count = d[1];
//         var index = d[2];
//         if (count > 0) {
//             loadCity(areaData[index].mallCityList);
//         } else {
//             $form.find('select[name=city]').parent().hide();
//         }
//     });
// }
// //加载市数据
// function loadCity(citys) {
//     var cityHtml = '';
//     var addHtml = '<option value="">请选择市/州</option>';
//     for (var i = 0; i < citys.length; i++) {
//         cityHtml += '<option value="' + citys[i].cityCode + '_' + citys[i].mallAreaList.length + '_' + i + '">' + citys[i].cityName + '</option>';
//     }
//     $form.find('select[name=city]').html(addHtml+cityHtml).parent().show();
//     form.render();
//     form.on('select(city)',
//     function(data) {
//         city = $('select[name=city]').find("option[value="+data.value+"]").text();
//         console.log(city);
//         var value = data.value;
//         var d = value.split('_');
//         var code = d[0];
//         var count = d[1];
//         var index = d[2];
//         if (count > 0) {
//             loadArea(citys[index].mallAreaList);
//         } else {
//             $form.find('select[name=area]').parent().hide();
//         }
//     });
// }
// //加载县/区数据
// function loadArea(areas) {
//     var areaHtml = '';
//     var addHtml = '<option value="">请选择县/区</option>';
//     for (var i = 0; i < areas.length; i++) {
//         areaHtml += '<option value="' + areas[i].areaCode + '">' + areas[i].areaName + '</option>';
//     }
//     $form.find('select[name=area]').html(addHtml+areaHtml).parent().show();
//     form.render();
//     form.on('select(area)',
//     function(data) {
//         // console.log(data);
//         area = $('select[name=area]').find("option[value="+data.value+"]").text();
//         console.log(area);
//     });
// }

// 手机端loading动画
function load() {
    loadBox = layer.open({
        type: 2,
        content: '玩命加载中'
    });
}

function expressType(type) {
    var str = '';
    if (type == '0') {
        str = '未获取订单';
    }else if (type == '1') {
        str = '顺丰标快';
    }else if (type == '2') {
        str = '顺丰特惠';
    }else if (type == '3') {
        str = '电商特惠';
    }else if (type == '5') {
        str = '顺丰次晨';
    }else if (type == '6') {
        str = '顺丰即日';
    }else if (type == '7') {
        str = '电商速配';
    }else if (type == '15') {
        str = '生鲜速配';
    }else{
        str = '未知状态';
    }
    return str;
}

function expressStatus(status) {
    var str = '';
    if (status == '0') {
        str = '未获取订单';
    }else if (status == '1') {
        str = '人工确认';
    }else if (status == '2') {
        str = '准备收件';
    }else if (status == '3') {
        str = '不可收派';
    }else if (status == '9') {
        str = '订单初始化中,请点击右侧更新按钮进行获取单号';
    }
    return str;
}


function notice() {
    layer.open({
        content: '程序员正在加班中,精彩内容稍后呈现!'
        ,skin: 'msg'
        ,time: 2 //2秒后自动关闭
      });
}

// 后台全局公用搜索
function searchAll() {
    var keyWord = $('input[name="keywords"]').val();
    if (keyWord == '') {
        layer.msg('搜索的内容不能为空!', function(){
            //关闭后的操作
        });
    } else {
        loading();
        $.post('http://jaclub.shareshenghuo.com/admin/search',{
            "keyWord" : keyWord
        },function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.close(loadingBox);
                if (obj.data.user_sex == 1) {
                    var sex = '男'
                } else {
                    var sex = '女'
                }
                var rank = '未知状态'
                switch (obj.data.user_rank) {
                    case 0:
                        rank = '普通用户'
                        break;
                    case 1:
                        rank = '体验会员'
                        break;
                    case 2:
                        rank = '男爵会员'
                        break;
                    case 3:
                        rank = '子爵会员'
                        break;
                    case 4:
                        rank = '伯爵会员'
                        break;
                    case 5:
                        rank = '侯爵会员'
                        break;
                    case 6:
                        rank = '公爵会员'
                        break;
                    default:
                        rank = '未知级别'
                        break;
                }
                if (obj.data.user_uid == '') {
                    var isUid = '否';
                } else {
                    var isUid = '是';
                }
                if (obj.data.rtsh_protocol == 1) {
                    var rtshProtocol = '已签署'
                } else {
                    var rtshProtocol = '未签署'
                }
                if (obj.data.uuid == '') {
                    var join = ''
                } else {
                    if (obj.data.protocol == 1) {
                        var protocol = '加盟商协议'
                    } else {
                        var protocol = '春蚕协议'
                    }
                    var join = '<p>加盟商信息</p><ul><li><span>加盟协议:'+protocol+'</span><span>基本积分:'+obj.data.point+'</span></li><li><span>赠送积分:'+obj.data.point_give+'</span><span>返佣积分:'+obj.data.point_fund+'</span></li><li><span>梦享家现金:'+obj.data.join_cash+'</span><span>债权收益:</span></li><li><span>产权收益:</span><span>春蚕现金:'+obj.data.price+'</span></li><li><span>开始时间:'+obj.data.spring_start+'</span><span>结束时间:'+obj.data.spring_end+'</span></li><li><span>春蚕状态:</span><span>待增加:</span></li></ul>'
                }
                var html = '<div class="layerInfo" style="padding: 1rem;"><ul><li><span>用户姓名:'+obj.data.user_name+'</span><span>用户电话:'+obj.data.user_phone+'</span></li><li><span>所在地区:'+obj.data.city+'</span><span>用户性别:'+sex+'</span></li><li><span>用户等级:'+rank+'</span><span>是否实名:'+isUid+'</span></li><li><span>基本积分:'+obj.data.user_point+'</span><span>余额账户:'+obj.data.user_price+'</span></li><li><span>融通协议:'+rtshProtocol+'</span><span>债权余额:'+obj.data.rtsh_bond+'</span></li></ul>'+join+'</div>';
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    shadeClose: true,
                    skin: 'yourclass',
                    area: ['500px', '50%'],
                    content: html
                });
            }else{
                layer.close(loadingBox);
                layer.msg(obj.msg, function(){
                    //关闭后的操作
                });
            }
        });
    }

}

function getOS() { // 获取当前操作系统
    var os;
    if (navigator.userAgent.indexOf('Android') > -1 || navigator.userAgent.indexOf('Linux') > -1) {
        os = 'android';
    } else if (navigator.userAgent.indexOf('iPhone') > -1) {
        os = 'ios';
    } else {
        os = 'Others';
    }
    return os;
}

function returnAndroid() {
    android.switchButton()
    console.log(1);
}

function returnIos() {
    window.webkit.messageHandlers.switchButton.postMessage('1')
}

function returnApp() {
    // return returnIos()
    if (getOS() == 'android') {
        returnAndroid()
    }else if (getOS() == 'ios') {
        returnIos()
    }else{
        alert('不支持当前手机的操作系统!')
    }
}

function back() {
    javascript:window.history.back();
}

// 返回函数
function result(status,msg) {
    if (status == 'success') {
        layer.msg(msg, {icon: 1});
                setTimeout(function () {
            location.reload();
        },1500)
    }else{
        layer.msg(msg, {icon: 2});
    }
}