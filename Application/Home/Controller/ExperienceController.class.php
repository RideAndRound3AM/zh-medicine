<?php 
	namespace Home\Controller;
    use Home\Controller\CommonController;  
	class ExperienceController extends CommonController{
		function index(){
    		if(IS_AJAX && IS_POST){
    		    $d=D('medicine');
    		    $arr=$d->showMedicineB();
    		    $this->assign('medicine',$arr);
    		    $ms=$this->fetch("addExperience");
    		    $this->myreturn($ms,1);
    		}
    	}
		/*
	    	@params:

	    	@return:
		*/
		function addExperience(){      //增加经验方
		    if(IS_AJAX && IS_POST){
		        //存功效表
                $d=D('efficacy');
                $arr=$d->where(Array('name'=>I('post.eff')))->select();
                if(count($arr)){
                    $eid=$arr[0]['id'];
                }else{
                    $d->add(Array('name'=>I('post.eff')));
                    $arr=$d->where(Array('name'=>I('post.eff')))->select();
                    $eid=$arr[0]['id'];
                }
                //存病症表
                $d=D('illness');
                $arr=$d->where(Array('name'=>I('post.ill')))->select();
                if(count($arr)){
                    $iid=$arr[0]['id'];
                }else{
                    $d->add(Array('name'=>I('post.ill')));
                    $arr=$d->where(Array('name'=>I('post.ill')))->select();
                    $iid=$arr[0]['id'];
                }
                //存经验方表
                $d=D('experience');
                $arr=$d->where(Array('name'=>I('post.exp')))->select();
                if(count($arr)){
                   $this->myreturn('已存在此经验方');
                }else{
                    $d->add(Array('name'=>I('post.exp'),'eid'=>$eid,'iid'=>$iid));
                    $arr=$d->where(Array('name'=>I('post.exp')))->select();
                    $expid=$arr[0]['id'];
                }
                //存药品经验方中间表
                $med=I('post.med');
                for($i=0;$i<count($med);$i=$i+3){
                    $m=[$med[$i],$med[$i+1],$med[$i+2]];
                    $n[]=$m;
                }
                $d=D('medicine');
                for($i=0;$i<count($n);$i++){
                    $arr=$d->getMerchantWayById(intval($n[$i][0]['value']));
                    $medid=intval($n[$i][0]['value']);
                    $dosage=intval($n[$i][1]['value']);
                    $remarks =$n[$i][2]['value'];
                    $way=intval($arr['way']);
                    $dd=D('medicine_experience');
                    $flag=$dd->add(Array('expid'=>$expid,'medid'=>$medid,'dosage'=>$dosage,'way'=>$way,'remarks'=>$remarks));
                }
                if($flag){
                    $this->myreturn('创建成功',1);
                }
            }
		}
		
	    /*
	    	@params:

	    	@return:
		*/

		function updateExperience(){    //修改经验方

		}

		/*
	    	@params:

	    	@return:
		*/
		
		function showExperience(){   //展现经验方
            if(IS_AJAX && IS_POST){
                $d=D('efficacy');
                $arr=$d->select();
                for($i=1;$i<count($arr)+1;$i++){
                    $a=$d->field('name')->find($i);
                    $allEff[$i]=$a['name'];
                }
                $d=D('illness');
                $arr=$d->select();
                for($i=1;$i<count($arr)+1;$i++){
                    $a=$d->field('name')->find($i);
                    $allIll[$i]=$a['name'];
                }   
                $d=D('experience');
                $arr=$d->select();
                for($i=0;$i<count($arr);$i++){
                    $name=$arr[$i]['name'];
                    $ename=$allEff[$arr[$i]['eid']];
                    $iname=$allIll[$arr[$i]['iid']];
                    $n=[$name,$iname,$ename];
                    $m[]=$n;
                }
                $this->myreturn($m,1);
            }
		}
		//查看经验方药品详情
		function showMed(){
		    if(IS_AJAX && IS_POST){
		        $d=D('experience');
		        $arr=$d->query("select med.name,zh.dosage,zh.remarks from t_experience as exp join t_medicine_experience as zh on(exp.id=zh.expid) join t_medicine as med on(zh.medid=med.id) where exp.name=%d",I('post.exp'));
		        var_dump($arr);
		    }
		}
	}
?>