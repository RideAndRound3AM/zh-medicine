	//配药发药页面的js
	function send(url,data,fn){
		$.ajax({
			url:MODULE+url,
			type:"post",
			data:data,
			success:fn
		})
	}
	function dec(classname,fn){
		$(document).on("click",classname,fn)
	}
	
	var tak=function(){
	
		/*************************配药页面****************************************/
		//配药页面，查询按钮js
		dec(".allPayIndentBtn",function(){
			send("/Indent/showAllPayIndent",{},function(msg){
				if(msg.status==1){
						$("#show_pay_table").html(msg.content);
					}else{
						$("#show_pay_table").html(msg.content);
					}
			})	
		})
		//查看处方
		dec(".check_prescribe",function(){
			var that=$(this).attr("check_prescribe_id");
			send("/Indent/showCurrenIndentPrescribe",{indentid:that},function(msg){
				if(msg.status==1){
					$(".all_prescribe_info").html(msg.content);
				}else{
					$(".all_prescribe_info").html(msg.content);
				}
			})	
		})
		//查看药品
		dec('.check_medicine_btn',function(){
			var prescribe_id=$(this).attr('prescribeid');
			send("/Indent/showCurrenPrescribeMedicine",{prescribeid:prescribe_id},function(msg){
				if(msg.status==1){
					$(".all_prescribe_medicine_info").html(msg.content);
					console.log(msg)
				}else{
					$(".all_prescribe_medicine_info").html(msg.content);
				}
			})
		})
		//药品分页
		dec(".all_medicine_pagecut>ul>li>a",function(){
			var num=$(this).attr('num');
			var prescribeid=$(this).parent().parent().parent().attr("prescribe_id");
			if(num){
				send("/Indent/showCurrenPrescribeMedicine",{'p':num,'prescribeid':prescribeid},function(msg){
					if(msg.status==1){
						$(".all_prescribe_medicine_info").html(msg.content);
					}else{
						$(".all_prescribe_medicine_info").html(msg.content);
					}
				})
			}
		})
		//配药时，确认配药
		dec(".take_medicine_btn",function(){
			var indent_id=$(this).attr("indentid");
			var step=$(this).parent().parent();
			bootbox.confirm("核对处方单，确认配药？",function(result){
				if(result){
					send("/Indent/sureTakeMedicine",{indentid:indent_id},function(msg){
						if(msg.status==1){
							step.remove();
						}else{
							alert(msg.content);
						}
					})
				}else{
					console.log('no')
				}
			})	
		})
		//订单分页
		dec(".take_med_page>ul>li>a",function(){
			var num=$(this).attr('num');
			if(num){
				send("/Indent/showAllPayIndent",{'p':num},function(msg){
					if(msg.status==1){
						$("#show_pay_table").html(msg.content);
					}else{
						$("#show_pay_table").html(msg.content);
					}
				})
			}
		})
		/*************************发药页面***************************************/
			//发药界面，获取卡号按钮
		dec(".queryTakeBtn",function(){
			send("/Indent/showQueryMedicine",{card:$(".queryTakeInfo").val()},function(msg){
			if(msg.status==1){
					$("#changeTakeBody").html(msg.content);
				}else{
					$("#changeTakeBody").html(msg.content);
				}
			})
		})
		//发药分页
		dec(".all_takemed_pagecut>ul>li>a",function(){
			var num=$(this).attr("num");
			var card=$(this).parent().parent().parent().attr("card");
			send("/Indent/showQueryMedicine",{"p":num,"card":card},function(msg){
				if(msg.status==1){
					$("#changeTakeBody").html(msg.content);
				}else{
					$("#changeTakeBody").html(msg.content);
				}
			})
		})
		//确认发药
		dec(".sure_takemed",function(){
			var indentid = $(this).attr("indentid");
			var that = $(this).parent().parent();
			bootbox.confirm("核对处方单，确认发药？",function(result){
				if(result){
					send("/Indent/surePatientTakeMedicine",{'indentid':indentid},function(msg){
						if(msg.status==1){
							that.remove();
						}else{
							alert(msg.content);
						}
					})
				}else{
					console.log(0)
				}
			})
		})
	}
	
	tak();
	
	
	
	
	
	
	
	
