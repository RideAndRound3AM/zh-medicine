var medicineNeed=function(){
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

	onclick("#addMedi",function(){	//增加药品
		$('#appendTbody').append(str);
		$(".chosen-select").chosen();
	});
	
	onclick(".remMedine",function(){	//删除
		$(this).parent().parent().remove();
	});
	
//增加申领单
	onclick(".add-need",function(){
		send(MODULE+"/Need/addDrawMedicine",
			"",
			function(msg){
				if(msg['status']==1){
					$('.mybody').html(msg['content']);
					$('.chosen-select').chosen();
			}
		});
	});
		
//查看申领单详情
	onclick(".need-details",function(){
		var id=$(this).attr("id-num");
		send(MODULE+"/Need/getNeedDetails",
			{needId:id},
			function(msg){
			    $(".need-medicines").html(msg["content"]);
		});
	});
//发送请求，导入低储药品
	onclick(".lead-medicine",function(){
		send(MODULE+"/Need/leadWlowMedicine",
			"",
			function(msg){
				if(msg["status"]==1){
					$("#appendTbody").append(msg["content"]);
					$(".chosen-select").chosen();
				}else{
					alert(msg["content"]);
				}
		});
	});

//发送请求，申领当前表单中的药品	
	onclick(".draw-medicine",function(){
		var data=$("#draw-med").serializeArray();
		var arr=[];
		$.each(data,function(i,j){
			if(j.value==""){
				arr.push(j.name);
			}
		});
		if(!arr.length){
			send(MODULE+"/Need/drawMedicine",
				{
					med:$("#draw-med").serializeArray()
				},
				function(msg){
					var tip=confirm(msg["content"]+"<br>是否跳转到药房药品申领界面？");
					if(tip){
						send(MODULE+"/Need/showDrawMedicine",
							"",
							function(msg){
								if(msg["status"]==1){
									$(".mybody").html(msg["content"]);
									$(".chosen-select").chosen();
								}else{
									alert(msg["content"]);
								}
						});
					}
				});
		}else{
			alert("请检查申领单是否完善");
		}
		});
	   
//输入药品时发送请求获取药品规格
$(document).on('change',".medi",function(){
	var medicineName=($(this).next().children(":first").text());
	var self=$(this);
	$.ajax({
		url:MODULE+"/Need/showMedicineStandard",
		type:"post",
		data:{
			med:medicineName
			},
		success:function(msg){
			var content=msg["standard"][0]["standard"];
			self.parent().next().next().children().children().val(content);
		}
	}) 
})
	return this;
}	
medicineNeed();

		   
