(function(){
		var inp;
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
			})
		})
		//增加行

			$(document).on('click',"#addMed",function(){
				$(inp).appendTo('#appendTbody');
				$('.chosen-select').chosen();
			})
		//删除行
			$(document).on('click',"#remMed",function(){
				$("#appendTbody").children(":last-child").remove();
			})

		//生成订单	
			$(document).on('click','#sub',function(){
				var a=0;
				$.each($('#mess').serializeArray(),function(i,j){
					if(j.value==''){
						 return a=1;
					}
				});
				if(a || $('#diagnosis').val()=='' || $('#total').val()==''){
					alert('再看看有什么没填！');
				}else{
					$.ajax({
						url:MODULE+"/Prescribe/addPrescribe",
						type:'post',
						data:{
							diagnosis:$('#diagnosis').val(),
							med:$('#mess').serializeArray(),
							total:$('#total').val()
						},
						success:function(msg){
							if(msg['status']==1){
								var flag=confirm("订单生成成功！");
								if(flag){
									$.ajax({
										url:MODULE+"/Prescribe/showPrescribe",
										type:'post',
										data:{
											card:$.cookie('card')
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
									})
								}
							}
						}
					})
				}
			})
		//查看历史处方
			$(document).on('click','#his',function(){
				$('#histable').html('');
				$.ajax({
					url:MODULE+"/Prescribe/getHistoryPrescribe",
					type:'post',
					data:{
						
					},
					success:function(msg){
						if(msg['status']==1 && msg['content']!=null){
							for(var i=0;i<msg['content'].length;i++){
								if(msg['content'][i][4]=='已缴费' || msg['content'][i][3]=='撤销'){
									$('#histable').append('<tr><td style="width:30%">'+msg['content'][i][0]+'</td><td style="width:14%">'+msg['content'][i][1]+'</td><td style="width:12%">'+msg['content'][i][2]+'</td><td style="width:12%">'+msg['content'][i][3]+'</td><td style="width:12%">'+msg['content'][i][4]+'</td><td style="width:20%"><div class="btn-group"><button type="button" id="reuse" class="btn btn-default btn-xs">复用</button></div></td</tr>')
								}else{
									$('#histable').append('<tr><td style="width:30%">'+msg['content'][i][0]+'</td><td style="width:14%">'+msg['content'][i][1]+'</td><td style="width:12%">'+msg['content'][i][2]+'</td><td style="width:12%">'+msg['content'][i][3]+'</td><td style="width:12%">'+msg['content'][i][4]+'</td><td style="width:20%"><div class="btn-group"><button type="button" id="reuse" class="btn btn-default btn-xs">复用</button><button id="del" type="button" class="btn btn-default btn-xs">撤销</button></div></td</tr>')
								}
								
							}
						}
						
					}
				})
			})
			
			//撤销处方
			$(document).on('click','#del',function(){
				var flag=confirm("是否撤销？");
				if(flag){
					$ctime=$(this).parent().parent().parent().children()[0].innerText;
					$tr=$(this);
					$.ajax({
						url:MODULE+"/Prescribe/removeHistoryPrescribe",
						type:'post',
						data:{
							ctime:$ctime
						},
						success:function(msg){
							if(msg['content']=='ok'){
								$tr.parent().parent().prev().prev()[0].innerHTML='撤销';
								$tr.remove();
							}
						}
					})
				}
			})
			
			//复用处方
			$(document).on('click','#reuse',function(){
				$ctime=$(this).parent().parent().parent().children()[0].innerText;
				$.ajax({
					url:MODULE+"/Prescribe/useHistoryPrescribe",
					type:'post',
					data:{
						ctime:$ctime
					},
					success:function(msg){
						console.log(msg);
						if(msg['status']==1){
							$('#diagnosis').val(msg['content'][0]);
							$('#total').val(msg['content'][1]);
							 for(var i=0;i<msg['content'][2].length;i++){
								if(i>0){
									$(inp).appendTo('#appendTbody');
									$('.chosen-select').chosen();
								}
								$('.chosen-single span')[i].value=msg['content'][2][i]['medid']*1;
								$('.chosen-single span')[i].innerText=$('#form-field-select-3 option')[msg['content'][2][i]['medid']].innerText;
								$('.input-small')[i].value=msg['content'][2][i]['dosage'];						
								$('.input-sm')[i].value=msg['content'][2][i]['remarks'];
							} 
						}
						
						
					}
				})
			})
			//默认用量
			$(document).on('change','.sele',function(){
				var dosage=$("option[value="+$('#mess').serializeArray()[0]['value']+"]").attr('dosage');
				$(this).parent().next().children(":first-child").children(":first-child").val(dosage);
			})

			jQuery(function($) {
				
				//新增的js代码，用于克隆tr节点，生成新的药品项
			/*	$('#addMedine').on('click',function(){
					$('#cloneTrNode').clone(true).append($("#appendTbody"));
				})*/
				//var abc=$('#cloneTrNode');
				
				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
						
				});
					$('#modal-form').on('shown.bs.modal', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
				})

					$(".chosen-select").chosen();
			
			
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			})
			
			
			function send(url,data,fn){
				$.ajax({
					url:MODULE+url,
					type:"post",
					data:data,
					success:fn
				})
			}
}());	