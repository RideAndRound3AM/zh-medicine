(function(){	

//修改药品售价按钮
			newClick('.mysellprice_update',function(){
				newAjax(MODULE+"/Medicine/getMedicineById",{
					id:$(this).attr("medid")
					},
					function(msg){
						if(msg['status']==1){
							$('.update_id').val(msg['content'].id);
							$('.update_name').val(msg['content'].name);
							$('.update_sellprice').val(msg['content'].sellprice);
						}else{
							alert(msg['content']);
						}	
				})
			});
//确认修改按钮
			newClick('.update_sellprice_button',function(){
				newAjax(MODULE+"/Medicine/UpdateMedicine",{
						id:$('.update_id').val(),
						name:$('.update_name').val(),
						sellprice:$('.update_sellprice').val()
					},function(msg){
						if(msg['status']==1){
							$(".tr_sellprice_"+$('.update_id').val()).html($('.update_sellprice').val());
						}else{
							alert(msg['content']);
						}
					})
			});

//搜索药品
			$('html').on('click','.sellprice_sear',function(){
				if($('.sellprice_search').val()!=''){
					newAjax(MODULE+"/Medicine/getMedicineByWhere_sellprice",{
						name:$('.sellprice_search').val()
					},function(msg){
						if(msg['status']==1){
							$('.mybody').html(msg['content']);
						}else{
							alert(msg['content']);
						}
					})
				}else{
					newAjax(MODULE+"/Medicine/getMedicine_sellprice",'',function(msg){
						if(msg['status']==1){
								$('.mybody').html(msg['content']);
							}else{
								alert(msg['content']);
							}
					})
				}
			})
//页码标签点击的ajax请求
			newClick('.sellprice_page>ul>li>a',function(){
				var num=$(this).attr('num');
				if(num){
					newAjax(MODULE+"/Medicine/getMedicine_sellprice",{'p':num},function(msg){
						if(msg['status']==1){
								$('.mybody').html(msg['content']);
							}else{
								alert(msg['content']);
							}
					})
				}
			})

}())

