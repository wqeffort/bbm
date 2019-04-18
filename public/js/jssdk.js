function actionQrcode() {
	wx.scanQRCode({
	    needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
	    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
	    success: function (res) {
	    	var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
	    	if (res.resultStr != 1) {
	    		layer.open({
				    content: '扫码失败，请稍后再试！'
				    ,skin: 'msg'
				    ,time: 2 //2秒后自动关闭
				});
	    	}else{
	    		layer.open({
				    content: '扫码失败，请稍后再试！'
				    ,skin: 'msg'
				    ,time: 2 //2秒后自动关闭
				});
	    	}
		}
	});
}

function openLocation() {
	wx.openLocation({
		longitude: lng, // 经度，浮点数，范围为180 ~ -180。
	    latitude: lat, // 纬度，浮点数，范围为90 ~ -90  
	    name: '123', // 位置名
	    address: '123', // 地址详情说明
	    scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
	    infoUrl: '123' // 在查看位置界面底部显示的超链接,可点击跳转
	});
}
