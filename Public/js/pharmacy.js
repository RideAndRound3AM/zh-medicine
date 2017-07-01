(function(){	

//修改药品按钮
			newClick('.myupdate',function(){
				newAjax(MODULE+"/Medicine/getMedicineById",{
					id:$(this).attr("medid")
					},
					function(msg){
						if(msg['status']==1){
							$('.update_id').val(msg['content'].id);
							$('.update_name').val(msg['content'].name);
							$('.update_area').val(msg['content'].area);
							$('.update_sort').val(msg['content'].sort);
						}else{
							alert(msg['content']);
						}	
				})
			});
//确认修改按钮
			newClick('.update_button',function(){
				newAjax(MODULE+"/Medicine/UpdateMedicine",{
						id:$('.update_id').val(),
						name:$('.update_name').val(),
						area:$('.update_area').val(),
						sort:$('.update_sort').val()
					},function(msg){
						if(msg['status']==1){
							$(".tr_area_"+$('.update_id').val())[0].textContent=$('.update_area').val();
							$(".tr_sort_"+$('.update_id').val())[0].textContent=$('.update_sort').val();
						}else{
							alert(msg['content']);
						}
					})
			});
//添加药品
			newClick('.insert_button',function(){
				newAjax(MODULE+"/Medicine/addMedicine",{
					name:$('.insert_name').val(),
					area:$('.insert_area').val(),
					sort:$('.insert_sort').val(),
					standard:$('.insert_standard').val()
				},function(msg){
						if(msg['status']==1){
							$('.mybody').html(msg['content']);		
						}else{
							alert(msg['content']);
						}
					})
			});
//搜索药品
			$('html').on('click','.pharmacy_search',function(){
				if($('.mysearch').val()!=''){
					newAjax(MODULE+"/Medicine/getMedicineByWhere_pharmacy",{
						name:$('.mysearch').val()
					},function(msg){
						if(msg['status']==1){
							$('.mybody').html(msg['content']);
						}else{
							alert(msg['content']);
						}
					})
				}else{
					newAjax(MODULE+"/Medicine/getMedicine_pharmacy",'',function(msg){
						if(msg['status']==1){
								$('.mybody').html(msg['content']);
							}else{
								alert(msg['content']);
							}
					})
				}
			})

//页码标签点击的ajax请求
			newClick('.pharmacy_page>ul>li>a',function(){
				var num=$(this).attr('num');
				if(num){
					newAjax(MODULE+"/Medicine/getMedicine_pharmacy",{'p':num},function(msg){
						if(msg['status']==1){
								$('.mybody').html(msg['content']);
							}else{
								alert(msg['content']);
							}
					})
				}
			})

}())

