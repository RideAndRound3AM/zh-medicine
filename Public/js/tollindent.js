//收费页面的js
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
var toll = function(){
	//查询病人卡号，发送ajax获取信息，返回订单数据
	dec(".queryBtn",function(){
		send("/Indent/showTollIndent",{card:$(".queryIndent").val()},function(msg){
			if(msg.status==1){
					console.log(msg)
					$("#intable").html(msg.content);
				}else{
					$("#intable").html(msg.content);
				}
		})
	})
	//缴费
	dec(".pay",function(){
		//需要在第一次点击事件中把订单id保存
		var indent_data=$(this).attr("indentid");
		//把下面需要修改的jQuery对象进行保存,即对应行的包含tollname的td
		var tollname_update=$(this).parent().parent();
		bootbox.confirm("确认缴费？",function(resurt){
			if(!resurt){
				console.log("取消缴费");
				return;
			}
			send("/Indent/confirmToll",{indentid:indent_data},function(msg){
				if(msg.status==1){
						tollname_update.hide();
					}else{
						tollname_update.html(msg.content);
					}
			})
		})
	})
	//分页
	dec(".all_tollIndent_pagecut>ul>li>a",function(){
		var num=$(this).attr('num');
		var card=$(this).parent().parent().parent().attr('card');
		if(num){
			send("/Indent/showTollIndent",{'p':num,'card':card},function(msg){
				if(msg.status==1){
					$("#intable").html(msg.content);
				}else{
					$("#intable").html(msg.content);
				}
			})
		}
	})
}
toll();

			
				
				
