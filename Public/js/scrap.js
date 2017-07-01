(function(){
	//选择药品后页面相应改变
	$('html').on('change','.myselect',function(){
		var warehouse=$(this).find('option:selected').attr('warehouse');
		var standard=$(this).find('option:selected').attr('standard');
		$(this).parent().next().text(warehouse);
		$(this).parent().next().next().next().text(standard);
	})
	//删除一行按钮效果
	newClick('.rowdelete',function(){
		if($('.startrow').length>1){
			$(this).parent().parent().parent().remove();
		}
	})
	//增加一行按钮
	newClick('.addrow_button',function(){
		var tmphtml=$('.startrow')[0].outerHTML;
		$('.scraptbody').append(tmphtml);
		$('.startrow').last().find('.scrapwarehouse').text('');
		$('.startrow').last().find('.scrapstandard').text('');
	})
	//固定报废数量的格式
	$('html').on('change','.scrapinput',function(){
		if($(this).val()*1!=$(this).val()){
			alert('输入格式有误');
			$(this).val('');
		}
		if($(this).val()*1>$(this).parent().prev().text()*1){
			alert('报废数量大于 库存，请从新输入!');
			$(this).val('');
		}
	})
	//确认报废按钮点击事件
	newClick('.scrap_button',function(){
		var arr=$('.scrapform').serializeArray();
		var arr1=[];
		for(var i=0;i<arr.length;i++){
			if(i%2==0){
				var arr2= new Object();
				arr2['medid']=arr[i]['value'];
			}else if(arr[i]['value']!=''){
				arr2['num']=arr[i]['value'];
				arr1.push(arr2);
			}
		}
		newAjax(MODULE+"/Scrap/addScrap",{
				datas:arr1
			},function(msg){
				if(msg['status']==1){
					$('.mybody').html(msg['content']);
					alert('报废成功');
				}else{
					alert(msg['content']);
				}
		})
	})
	
	
	
	
	
	
	
	
	
	
	
	
}());