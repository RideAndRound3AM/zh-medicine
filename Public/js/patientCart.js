(function(){
	
	/*var inp;
	$(document).on('click','#fat-btn',function(){
		$.cookie('card',$('#card').val());
		send("/Prescribe/showPrescribe",{card:$('#card').val()},function(msg){
			if(msg['status']==1){
				$('.mybody').html(msg['content']);
				inp=$('#appendTbody').html();
				$('.chosen-select').chosen();
			}else{
				alert(msg['content']);
			}
		})*/
					/*$.ajax({
						url:MODULE+"/Prescribe/showPrescribe",
						type:'post',
						data:{
							card:$('#card').val()
						},
						success:function(msg){
							if(msg['status']==1){
								$('.mybody').html(msg['content']);
								inp=$('#appendTbody').html();
								$('.chosen-select').chosen();
							}else{
								alert(msg['content']);
							}
						}
					})*/
				/*})*/
			
	function send(url,data,fn){
		$.ajax({
			url:MODULE+url,
			type:"post",
			data:data,
			success:fn
		})
	}

})();