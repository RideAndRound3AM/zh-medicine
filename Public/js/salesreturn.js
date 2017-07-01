(function(){
	//选择药品后页面相应改变
	$('html').on('change','.myselect_medicine',function(){
		var standard=$(this).find('option:selected').attr('standard');
		$(this).parent().next().next().text(standard);
	})
	//删除一行按钮效果
	newClick('.sales_rowdelete',function(){
		if($('.sales_startrow').length>1){
			$(this).parent().parent().parent().remove();
		}
	})
	//增加一行按钮
	newClick('.addrow_sales_button',function(){
		var tmphtml=$('.sales_startrow')[0].outerHTML;
		$('.salestbody').append(tmphtml);
		$('.sales_startrow').last().find('.salesstandard').text('');
	})
	//固定退还数量的格式
	$('html').on('change','.salesinput',function(){
		if($(this).val()*1!=$(this).val()){
			alert('输入格式有误');
			$(this).val('');
		}
		$warehouse=$(this).parent().prev().find('option:selected').attr('warehouse')*1;
		if($(this).val()*1>$warehouse){
			alert('退还数量大于 库存，请从新输入!');
			$(this).val('');
		}
	})
	//确认退还按钮点击事件
	newClick('.sales_button',function(){
		var arr=$('.saresform').serializeArray();
		var arr1=[];
		for(var i=0;i<arr.length;i++){
			if(i%5==0){
				var arr2= new Object();
				arr2['medid']=arr[i]['value'];
			}else{
				var temp=arr[i]['name'];
				arr2[temp]=arr[i]['value'];
				if(i%5==4){
					arr1.push(arr2);
				}
			}
		}
		newAjax(MODULE+"/Salesreturn/addSalesreturn",{
				datas:arr1
			},function(msg){
				if(msg['status']==1){
					$('.mybody').html(msg['content']);
					alert('退货成功');
				}else{
					alert(msg['content']);
				}
		})
	})
	
	
}());