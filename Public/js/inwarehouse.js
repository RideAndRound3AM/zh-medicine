var inwarehouse=function(){
	function onclick(dom,fun){		
		$(document).on('click',dom,fun);
	}
	function send(url,data,fun){	//通用ajax请求
		$.ajax({
			url:url,
			type:"post",
			data:data,
			success:fun
		})
	}
	
	onclick("#addInwarehouse",function(){	//增加药品
		$(".inwarehouse-body").append(buyTable);
		$(".chosen-select").chosen();
	});
	
	onclick("#remInwarehouse",function(){	//删除药品
		$(this).parent().parent().remove();
	});
	
	onclick(".inwarehouse-details",function(){	//查看入库单详情
		console.log("1");
		var inwarehouseId=$(this).attr("inwarehouse-id");
		send(MODULE+"/Inwarehouse/showInwarehouseDetails",
			{id:inwarehouseId},
			function(msg){
				if(msg["status"]=1){
					$(".all-details").html(msg["content"]);
				}
			});
	});
	
	onclick(".add-inwarehouse",function(){		//增加入库单
		send(MODULE+"/Inwarehouse/inwarehouse",
			"",
			function(msg){
			$('.mybody').html(msg["content"]);
			$(".chosen-select").chosen();
		});
	});
	
	onclick(".buy-inwarehouse",function(){	//查看所有未入库的采购单
		send(MODULE+"/Inwarehouse/showBuys",
			"",
			function(msg){
				if(msg["status"]=1){
					$(".buys").html(msg["content"]);
				}
		});
	});
	
	onclick(".buydetails",function(){		//查看采购单详情
		
		var id=$(this).attr("bid");
		send(MODULE+"/Inwarehouse/showBuyDetails",
			{buyId:id},
			function(msg){
				if(msg["status"]=1){
					$(".inwarehouse-buy").text("采购单详情");
					$(".buys").html(msg["content"]);	
				}
			}
		);
	});
	
	onclick(".lead-buy",function(){		//导入采购单
		var buyId=$(this).attr("inw-buy-id");
		var bid=$(".inwarehouse-buy-id").val();
		if(bid){
			alert("你已经选择了其他采购单，不能再导入了！");
		}else{
			send(MODULE+"/Inwarehouse/leadBuy",
					{id:buyId},
					function(msg){
						if(msg["status"]=1){
							$(".inwarehouse-buy-id").val(buyId);
							$(".inwarehouse-body").append(msg["content"]);
							$(".chosen-select").chosen();
						}
					});
		}
	});
	
	onclick("#affirm-inwarehouse",function(){
		var inwarehouseBatch=$(".invoice").val();
		var buyId=$(".inwarehouse-buy-id").val();
		var data=$(".inwarehouse-medicine").serializeArray();
		var arr=[];
		$.each(data,function(i,j){
			if(j.value==""){
				arr.push(j.name);
			}
		});
		if(!arr.length && buyId && data){
			send(MODULE+"/Inwarehouse/affirmInwarehouse",
					{
						invoice:inwarehouseBatch,
						bid:buyId,
						medicineInfo:$(".inwarehouse-medicine").serializeArray()
					},
					function(msg){
						if(msg["status"]=1){
							alert(msg["content"]);
							send(MODULE+"/Inwarehouse/inwarehouse",
									"",
									function(msg){
										if(msg["status=1"]){
											$('.mybody').html(msg["content"]);
											$(".chosen-select").chosen();
										}
								});
						}
					});
		}else{
			alert("请检查你的入库信息是否完善");
		}	
	});
	
	return this;
}

inwarehouse();