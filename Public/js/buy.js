var buy=function(){
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
	onclick('#addMedicine',function(){		//增加采购药品
		$('#appendTbody').append(buyTable);
		$(".chosen-select").chosen();
	});
	
	onclick("#remMedicine",function(){		//删除
		$(this).parent().parent().remove();
	});
	
	onclick(".buy-details",function(){		//药库查看已有采购单详情
		var buyId=$(this).attr("buy-id");
		send(MODULE+"/Buy/getBuyDetails",
			{buyId:buyId},
			function(msg){
				if(msg["status"]==1){
					$(".buy-detail").html(msg["content"]);
				}			
			});
	});
	
	onclick(".buy-deta",function(){
		var buyId=$(this).attr("buy-id");
		send(MODULE+"/Buy/showBuyDetail",
			{buyId:buyId},
			function(msg){
				if(msg["status"]==1){
					$(".buy-detail").html(msg["content"]);
				}			
			});
	});
	
	onclick(".add-buy",function(){		//增加采购单
		send(MODULE+"/Buy/addBuy",
			"",
			function(msg){
				if(msg['status']==1){
					$('.mybody').html(msg['content']);
					$('.chosen-select').chosen();
			}
		});
	})
	
	
	onclick(".department",function(){		// 提交申请，查看当前所有部门申领单
		send(MODULE+"/Buy/showNeeds",
			"",
			function(msg){
					if(msg["status"]==1){
						$(".needs").html("");
						$(".needs").html(msg["content"]);
					}					
				});
	});
	
	onclick('.look',function(){			//提交申请，查看点击的部门申领单详情
		var id=$(this).parent().parent().children(":first").attr('id');
		send(MODULE+"/Buy/showNeed",
		{needId:id},
		function(msg){
			if(msg["status"]==1){
				$(".need").html(msg["content"]);	
			}
		}
		);
	});
	
		newClick(".load-need",function(){		//发送请求，导入当前部门申领单
			/*id=$(this).attr('needid');*/
			newAjax(MODULE+"/Buy/loadNeed",{
				needId:$(this).attr('needid')
			},function(msg){
				if(msg["status"]==1){
					$(".buy-body").append(msg["content"]);
					$(".chosen-select").chosen();
				}
			});
			/*var medicinesName=$(".medicine-name");
			var medicinesNum=$(".medicine-num");
			var medicineName=[];
			var medicineNum=[];
			for(i=0;i<medicinesName.length;i++){
				medicineName[i] = medicinesName[i].getAttribute("name");
				medicineNum[i] = medicinesNum[i].getAttribute("num");
 			}
			send(MODULE+"/Buy/loadNeed",
				{
					medicineName:medicineName,
					medicineNum:medicineNum
				},
				function(msg){
					if(msg["status"]=1){
						$(".buy-body").append(msg["content"]);
						$(".chosen-select").chosen();
					}
				}
			);*/
		});
		
		onclick(".get-history",function(){
			send(MODULE+"/Buy/showHistoryBuy",
				"",
				function(msg){
					$(".history-buy").html(msg["content"]);
				}
			);
		});
		
		onclick(".load-buy",function(){	//发送请求，导入历史未供货药品
			var medicinesName=$(".medname");
			var medicinesNum=$(".mednum");
			var medicineName=[];
			var medicineNum=[];
			for(i=0;i<medicinesName.length;i++){
				medicineName[i] = medicinesName[i].getAttribute("name");
				medicineNum[i] = medicinesNum[i].getAttribute("num");
 			}
			send(MODULE+"/Buy/loadBuy",
				{
					medicineName:medicineName,
					medicineNum:medicineNum
				},
				function(msg){
					if(msg["status"]==1){
						$(".buy-body").append(msg["content"]);
						$(".chosen-select").chosen();
					}
				}
			);
		});
		
		onclick(".affirm-buy",function(){
			var data=$(".buy-medicine").serializeArray();
			var arr=[];
			$.each(data,function(i,j){
				if(j.value==""){
					arr.push(j.name);
				}
			});
			if(!arr.length){
				send(MODULE+"/Buy/affirmBuyPlan",
						{buyInfo:$(".buy-medicine").serializeArray()},
						function(msg){
							if(msg["status"]==1){
								var tips=confirm(msg["content"]+"是否需要跳转到采购详情界面？");
								if(tips){
									send(MODULE+"/Buy/showBuy",
										"",
										function(msg){
											if(msg["status"]=1){
												$(".mybody").html(msg["content"]);
											}
										});
									}
								}
							});
				}else{
					alert("请检查采购计划信息是否完善");
			}	
		});
		
		onclick(".affirm-Examine-buy",function(){  //确认审批
			var buyId=$(this).attr("affirm-Examine-buy-id");
			var examineResult=$(this).parent().prev().children(":last").children(":first").text();
			var self=$(this).parent().parent();
			if(buyId && examineResult){
				send(MODULE+"/Buy/affirmExamineBuy",
						{
							id:buyId,
							status:examineResult
						},
						function(msg){
							if(msg["status"]==1){
								alert(msg["content"]);
								self.remove();
							}else{
								alert("审批失败，请查看是否有审批内容");
							}
							
						});
			}
			
		});
		
		onclick(".examine-medicine",function(){
			var medId=$(this).attr("medid");
			var buyId=$(this).attr("bid");
			send(MODULE+"/Buy/examineMedicine",
				{
					medid:medId,
					bid:buyId
				},
				function(msg){
					if(msg["status"]==1){
						alert(msg["content"]);
						send(MODULE+"/Buy/getBuyDetails",
								{buyId:buyId},
								function(msg){
									if(msg["status"]==1){
										$(".buy-detail").html(msg["content"]);
									}			
								});
					}else{
						alert(msg["content"]);
					}
				}
				)
		})
		
	return this;
}
buy();
