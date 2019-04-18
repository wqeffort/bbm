@extends('lib.home.header')
@section('body')
<body ng-app="mainApp" ng-controller="indexCtrl">
		<div class="headerWrapper container-fluid">
		</div>
	<style type="text/css">
	.lotteryBox{
		padding-top: 1rem;
	}
	.lotteryBox ul {
		display: flex;
	    justify-content: space-between;
	    height: 3rem;
	    line-height: 3rem;
	    padding: 0 2rem;
	}
	.lotteryBox ul li img {
		width: 2rem;
    	border-radius: 50%;
    	border: 3px solid #FFF;
    	margin-top: .25rem;
	}
	</style>
		<main>
			<div class="draw" id="lottery">
				<img src="{{ asset('images/temp/lottery.png') }}" style="    width: 100%;
    position: absolute;
    z-index: -1;">
				<table id="table" style="position: absolute;">
					<tr>
						<td class="item lottery-unit lottery-unit-0">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-01.png') }}" alt="">
							</div>
							{{-- <span class="name">8</span> --}}
						</td>
						<td class="gap"></td>
						<td class="item lottery-unit lottery-unit-1">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-03.png') }}" alt="">
							</div>
							{{-- <span class="name">1</span> --}}
						</td>
						<td class="gap"></td>
						<td class="item lottery-unit lottery-unit-2">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-06.png') }}" alt="">
							</div>
							{{-- <span class="name">2</span> --}}
						</td>
					</tr>
					<tr>
						<td class="gap-2" colspan="5"></td>
					</tr>
					<tr>
						<td class="item lottery-unit lottery-unit-7">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-04.png') }}" alt="">
							</div>
							{{-- <span class="name">7</span> --}}
						</td>
						<td class="gap"></td>
						<td @if (!$lotteryStatus)
							onclick="go()"
						@endif class="item_btn" style="background: rgb(177, 0, 0);border-radius: .5rem;">
							<img class="draw-btn" src="{{ asset('images/temp/lottery-05.png') }}">
						</td>
						<td class="gap"></td>
						<td class="item lottery-unit lottery-unit-3">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-02.png') }}" alt="">
							</div>
							{{-- <span class="name">3</span> --}}
						</td>
					</tr>
					<tr>
						<td class="gap-2" colspan="5"></td>
					</tr>
					<tr>
						<td class="item lottery-unit lottery-unit-6">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-09.png') }}" alt="">
							</div>
							{{-- <span class="name">6</span> --}}
						</td>
						<td class="gap"></td>
						<td class="item lottery-unit lottery-unit-5">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-03.png') }}" alt="">
							</div>
							{{-- <span class="name">5</span> --}}
						</td>
						<td class="gap"></td>
						<td class="item lottery-unit lottery-unit-4">
							<div class="img">
								<img class="lotteryGoods" src="{{ asset('images/temp/lottery-01.png') }}" alt="">
							</div>
							{{-- <span class="name">4</span> --}}
						</td>
					</tr>
				</table>
			</div>
		</main>
		<div class="lottery">
			<img style="width: 100%;" src="{{ asset('images/temp/lottery-07.png') }}">
			<div class="lotteryBox" style="width: 100%">
				@if ($openLotteryInfo->isNotEmpty())
					@foreach ($openLotteryInfo as $element)
						<ul>
					<li><img src="{{ asset($element->user_pic) }}"></li>
					<li>{{ $element->user_nickname }}</li>
					<li>@switch($element->lottery_key)
					    @case(1)
					        中了 <b>一等奖</b>
					        @break
					    @case(2)
					        中了 <b>二等奖</b>
					        @break
						@case(3)
					        中了 <b>三等奖</b>
					        @break
					    @case(4)
					        中了 <b>四等奖</b>
					        @break
					    @case(5)
					        中了 <b>五等奖</b>
					        @break
					    @default
					            中了 <b>幸运奖</b>
					@endswitch
					</li>
				</ul>
					@endforeach
				@endif

			</div>
		</div>
		<script type="text/javascript">
			var box = '';
			// 自适应整个label
			var width = $(window).width() * .8;
			var t = width * .535;
			var l = width * .125;
			var img = width * .3;
			var lottery = width * 1.7;
			var lotteryBox = width * .195;
			$("#table").css({
				"width" : width,
				"height" : width,
				"position": "absolute",
				"top" : t,
				"left" : l
			})
			$(".draw .item").css({
				"width" : width/3,
				"height" : width/3,
			})
			$(".item_btn").css({
				"width" : width/3,
				"height" : width/3,
			})
			$(".lotteryGoods").css({
				"width" : img
			})
			$(".lottery").css({
				"width" : "100%",
				"position": "absolute",
				"top" : lottery
			});
			$(".lotteryBox").css({
			    "width" : "100%",
			    "background" : "#FFE718",
			    "position" : "absolute",
			    "top" : lotteryBox,
			});
			var lottery = {
				index: -1, //当前转动到哪个位置，起点位置
				count: 0, //总共有多少个位置
				timer: 0, //setTimeout的ID，用clearTimeout清除
				speed: 20, //初始转动速度
				times: 0, //转动次数
				cycle: 50, //转动基本次数：即至少需要转动多少次再进入抽奖环节
				prize: -1, //中奖位置
				init: function(id) {
					if($('#' + id).find('.lottery-unit').length > 0) {
						$lottery = $('#' + id);
						$units = $lottery.find('.lottery-unit');
						this.obj = $lottery;
						this.count = $units.length;
						$lottery.find('.lottery-unit.lottery-unit-' + this.index).addClass('active');
					};
				},
				roll: function() {
					var index = this.index;
					var count = this.count;
					var lottery = this.obj;
					$(lottery).find('.lottery-unit.lottery-unit-' + index).removeClass('active');
					index += 1;
					if(index > count - 1) {
						index = 0;
					};
					$(lottery).find('.lottery-unit.lottery-unit-' + index).addClass('active');
					this.index = index;
					return false;
				},
				stop: function(index) {
					this.prize = index;
					return false;
				}
			};

			function roll() {
				lottery.times += 1;
				lottery.roll(); //转动过程调用的是lottery的roll方法，这里是第一次调用初始化
				if(lottery.times > lottery.cycle + 10 && lottery.prize == lottery.index) {
					clearTimeout(lottery.timer);
					setTimeout(function () {
						box = layer.open({
		    			type: 1
		    			,content: '<div id="info" style = "position: relative;" onclick="closeBox()">'+$("#info").html()+'</div>'
		    			,anim: 'up'
		    			,shadeClose: false
		    			,style: 'position: fixed;top: 15%;left: 0;width: 100%;height: 200px;padding: 10px 0;border: none;text-align: center;background: none;'
							});
					}, 1000)
						lottery.prize = -1;
						lottery.times = 0;
						click = false;
				} else {
					if(lottery.times < lottery.cycle) {
						lottery.speed -= 10;
					} else if(lottery.times == lottery.cycle) {
						// var index = Math.random() * (lottery.count) | 0; //静态演示，随机产生一个奖品序号，实际需请求接口产生
						// load();
						var index = {{ $key }};
						lottery.prize = index;
					} else {
						if(lottery.times > lottery.cycle + 10 && ((lottery.prize == 0 && lottery.index == 7) || lottery.prize == lottery.index + 1)) {
							lottery.speed += 110;
						} else {
							lottery.speed += 20;
						}
					}
					if(lottery.speed < 40) {
						lottery.speed = 40;
					};
					lottery.timer = setTimeout(roll, lottery.speed); //循环调用
				}
				return false;
			}

			var click = false;

			window.onload = function() {
				lottery.init('lottery');
				// 加载弹框
				@if ($lotteryStatus)
					var img = '<img style="height: 3rem;width: 10rem;" src="data:image/png;base64,{{ $lotteryCode }}" alt="barcode"   />'
				  	layer.open({
					    content: '您已经中奖,无法再继续抽奖<br>领奖码为:<br><br><br>'+img
					    ,btn: '我知道了'
					  });
				@endif
			}
			function go() {
				load();
				// 说明用户已经抽奖
				$.post('{{ url('setLottery') }}', {"_token": '{{ csrf_token() }}',"id":'{{ $id }}'}, function(ret) {
					var obj = $.parseJSON(ret);
					layer.close(loadBox);
					if (obj.status == 'success') {
						if(click) { //click控制一次抽奖过程中不能重复点击抽奖按钮，后面的点击不响应
							return false;
						}else{
							lottery.speed = 100;
							roll(); //转圈过程不响应click事件，会将click置为false
							click = true; //一次抽奖完成后，设置click为true，可继续抽奖
							return false;
						}
					}else{
						location.reload();
					}
				});
				
			}
			function closeBox() {
				layer.close(box);
				load();
				click = false;
				location.reload();
			}
		</script>
	</body>

	<div id='info' style = "position: relative; display : none" onclick="closeBox()">
	   <a href="javascript:;"><img style="width: 80%;
    margin-left: 10%;" src="{{ asset('images/temp/lottery-08.png') }}" alt=""><img style="    position: absolute;
    top: 10rem;
    left: 7rem;
    width: 10rem;" src="{{ asset($msg) }}"></a>
	</div>
@endsection