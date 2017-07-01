            //增加行
			$(document).on('click',"#addM",function(){
				$(row).appendTo('#appTbody');
				$('.chosen-select').chosen();
			})
		    //删除行
			$(document).on('click',"#remM",function(){
				$("#appTbody").children(":last-child").remove();
			})
			//创建经验方
			$(document).on('click','#set',function(){
				var flag=confirm("确定创建此经验方？");
				var a=0;
				if(flag){
					if($('#exp').val()=='' || $('#ill').val()==''|| $('#eff').val()==''){
						alert("再看看有什么没填的");
					}else{
						$.each($('#mess1').serializeArray(),function(i,j){
							if(j.value==''){
								 return a=1;
							}
						});
						if(a){
							alert("再看看有什么没填的");
						}else{
							$.ajax({
								url:MODULE+"/Experience/addExperience",
								type:"post",
								data:{
									exp:$('#exp').val(),
									ill:$('#ill').val(),
									eff:$('#eff').val(),
									med:$('#mess1').serializeArray()	
								},
								success(msg){
									if(msg['status']==0){
										alert('此经验方已存在！');
									}else{
										alert('创建成功！');
										$.ajax({
											url:MODULE+"/Experience/index",
											type:"post",
											data:{	
											},
											success(msg){
												if(msg['status']==1){
													$('.mybody').html(msg['content']);
													$('.chosen-select').chosen();
												}else{
													alert(msg['content']);
												}
											}
										})
									}
								}
							})
						}	
					}	
				}
			})
			//查看所有经验方
			$(document).on('click','#allExp',function(){
				$('#exptable').html('');
				$.ajax({
					url:MODULE+"/Experience/showExperience",
					type:'post',
					data:{
						
					},
					success:function(msg){
						if(msg['status']==1 && msg['content']!=null){
							for(var i=0;i<msg['content'].length;i++){
									$('#exptable').append('<tr><td style="width:25%">'+msg['content'][i][0]+'</td><td style="width:25%">'+msg['content'][i][1]+'</td><td style="width:25%">'+msg['content'][i][2]+'</td><td style="width:25%"><div class="btn-group"><button type="button" id="med" class="btn btn-default btn-xs">查看药品</button></div></td</tr>')		
							}
						}
						
					}
				})
			})
			//查看药品详情
			$(document).on('click','#med',function(){
				var expname=$(this).parent().parent().parent().children()[0].innerText;
				$.ajax({
					url:MODULE+"/Experience/showMed",
					type:'post',
					data:{
						exp:expname
					},
					success:function(){
						
					}
				})
			})