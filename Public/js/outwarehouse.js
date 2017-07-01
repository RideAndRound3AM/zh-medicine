//出库和退还js
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
var outwarehouse = function(){
	//选择药品后页面相应改变
	$(document).on('change','.med_back_info',function(){
		var pharmacy=$(this).find('option:selected').attr('pharmacy');
		var standard=$(this).find('option:selected').attr('standard');
		$(this).parent().next().text(pharmacy);
		$(this).parent().next().next().next().text(standard);
	})
	
	//删除行
	dec(".del_med_row",function(){
		var del=$(this).parent().parent().parent();
		var med_row = $(".step_add_row");
		console.log(med_row.length);
		bootbox.confirm("确定删除本行？",function(result){
			if(result && med_row.length>1){
				del.remove();
			}else{
				console.log(0)
			}
		})
	})
	
	//增加药品行
	dec(".add_ware_med",function(){
		var med_row_html = $(".step_add_row")[0].outerHTML;
		$(".step_add_tbody").append(med_row_html);
		$(".step_add_row").last().find(".out_pharmacy").text('');
		$(".step_add_row").last().find(".out_standard").text('');
	})
		//固定报废数量的格式
	$(document).on('change','.out_ware_input',function(){
		if($(this).val()*1!=$(this).val()){
			alert('输入格式有误');
			$(this).val('');
		}
		if($(this).val()*1>$(this).parent().prev().text()*1){
			alert('报废数量大于 库存，请从新输入!');
			$(this).val('');
		}
	})
		//确认退还药库
	dec('.sure_back_ware_btn',function(){
		var arr=$('.med_back_form').serializeArray();
		var arr1=[];
		for(var i=0;i<arr.length;i++){
			if(i%2==0){
				var arr2= new Object();
				arr2['id']=arr[i]['value'];
			}else{
				arr2['updatenum']=arr[i]['value'];
				arr1.push(arr2);
			}
		}
		
		send("/Outwarehouse/confirmBackMed",{datas:arr1},function(msg){
			if(msg['status']==1){
				$('.mybody').html(msg['content']);
				alert('退还成功!');
			}else{
				alert(msg['content']);
			}
		})
	})
}
outwarehouse();

















