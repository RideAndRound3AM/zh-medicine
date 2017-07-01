var arr=['salesreturn','outwarehouse','scrap','inwarehouse','addExperience','jquery-cookie','sellprice','warehouse_reservoir','pharmacy_reservoir','doctor','buy','need','pharmacy','merchant','takeindent','tollindent','header','addPrescribe','patientCart'];
var arrcss=[];
for(var i=0;i<arr.length;i++){
	document.write("<script src='"+PUBLIC+"/js/"+arr[i]+".js'></script>");
}
for(var i=0;i<arrcss.length;i++){
	document.write("<link rel='stylesheet' href='"+PUBLIC+"/css/"+arrcss[i]+".css' />");
}
//点击事件的方法
function newClick(classname,fun){
	$('html').on('click',classname,fun);
}
//ajax的方法
function newAjax(urls,datas,fun){
	$.ajax({
		url:urls,
		type:'post',
		data:datas,
		success:fun
	})
}