(function(){
//添加供货商按钮 
	newClick('.mymerchant_insert',function(){
		newAjax(MODULE+"/Merchant/addMerchant",{
				name:$('.mymerchant_add_name').val(),
				bankname:$('.mymerchant_add_bankname').val(),
				bankid:$('.mymerchant_add_bankid').val()
			},function(msg){
				if(msg['status']==1){
					$('.mybody').html(msg['content']);		
				}else{
					alert(msg['content']);
				}
			})
	})
	
//修改供货商按钮
	newClick('.mymerchant_update',function(){
		newAjax(MODULE+"/Merchant/getMerchantById",{
				id:$(this).attr('merid')
			},function(msg){
				if(msg['status']==1){
					if(msg['status']==1){
						$('.mymerchant_update_id').val(msg['content'].id);
						$('.mymerchant_update_name').val(msg['content'].name);
						$('.mymerchant_update_bankname').val(msg['content'].bankname);
						$('.mymerchant_update_bankid').val(msg['content'].bankid);		
					}else{
						alert(msg['content']);
					}
				}else{
					alert(msg['content']);
				}
			})
	})

//确认修改供货商按钮
	newClick('.mymerchant_update_btn',function(){
			var id_=$('.mymerchant_update_id').val();
			var name_=$('.mymerchant_update_name').val();
			var bankname_=$('.mymerchant_update_bankname').val();
			var bankid_=$('.mymerchant_update_bankid').val();
		newAjax(MODULE+"/Merchant/updateMerchant",{
			id:id_,
			name:name_,
			bankname:bankname_,
			bankid:bankid_
			},function(msg){
				if(msg['status']==1){
					console.log('蛤蟆皮');
					$(".tr_mer_name_"+id_).html(name_);
					$(".tr_mer_bankname_"+id_).html(bankname_);
					$(".tr_mer_bankid_"+id_).html(bankid_);
				}else{
					alert(msg['content']);
				}
			})
	})

	
//页码标签点击的ajax请求
	newClick('.merchant_page>ul>li>a',function(){
		var num=$(this).attr('num');
		if(num){
			newAjax(MODULE+"/Merchant/getMerchant",{
					p:num
				},function(msg){
					if(msg['status']==1){
						$('.mybody').html(msg['content']);
					}else{
						alert(msg['content']);
					}
				})
		}
	})
	
})()

//搜索供货商
	$('html').on('click','.merchant_search',function(){
				if($('.mymerchant_search').val()!=''){
					newAjax(MODULE+"/Merchant/getMerchantByWhere",{
						name:$('.mymerchant_search').val()
					},function(msg){
						if(msg['status']==1){
							$('.mybody').html(msg['content']);
						}else{
							alert(msg['content']);
						}
					})
				}else{
					newAjax(MODULE+"/Merchant/getMerchant",'',function(msg){
						if(msg['status']==1){
							$('.mybody').html(msg['content']);
						}else{
							alert(msg['content']);
						}
					})
				}
			})



