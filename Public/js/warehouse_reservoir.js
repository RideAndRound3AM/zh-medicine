(function(){	

//修改药品按钮
			newClick('.mywarehouse_res_update',function(){
				newAjax(MODULE+"/Medicine/getMedicineById",{
					id:$(this).attr("medid")
					},
					function(msg){
						if(msg['status']==1){
							$('.update_id').val(msg['content'].id);
							$('.update_name').val(msg['content'].name);
							$('.update_whigh').val(msg['content'].whigh);
							$('.update_wlow').val(msg['content'].wlow);
						}else{
							alert(msg['content']);
						}	
				})
			});
//确认修改按钮
			newClick('.update_warehouse_res_button',function(){
				if($('.update_whigh').val()*1>$('.update_wlow').val()*1){
					newAjax(MODULE+"/Medicine/UpdateMedicine",{
						id:$('.update_id').val(),
						name:$('.update_name').val(),
						whigh:$('.update_whigh').val(),
						wlow:$('.update_wlow').val()
					},function(msg){
						if(msg['status']==1){
							$(".tr_whigh_"+$('.update_id').val()).html($('.update_whigh').val());
							$(".tr_wlow_"+$('.update_id').val()).html($('.update_wlow').val());
						}else{
							alert(msg['content']);
						}
					})
				}else{
					alert('修改内容不合法!');
				}
			});

//搜索药品
			$('html').on('click','.warehouse_search',function(){
				if($('.warehouse_res_search').val()!=''){
					newAjax(MODULE+"/Medicine/getMedicineByWhere_warehouse_res",{
						name:$('.warehouse_res_search').val()
					},function(msg){
						if(msg['status']==1){
							$('.mybody').html(msg['content']);
						}else{
							alert(msg['content']);
						}
					})
				}else{
					newAjax(MODULE+"/Medicine/getwarehouse_res",'',function(msg){
						if(msg['status']==1){
								$('.mybody').html(msg['content']);
							}else{
								alert(msg['content']);
							}
					})
				}
			})
//页码标签点击的ajax请求
			newClick('.warehouse_res_page>ul>li>a',function(){
				var num=$(this).attr('num');
				if(num){
					newAjax(MODULE+"/Medicine/getwarehouse_res",{'p':num},function(msg){
						if(msg['status']==1){
								$('.mybody').html(msg['content']);
							}else{
								alert(msg['content']);
							}
					})
				}
			})

}())

