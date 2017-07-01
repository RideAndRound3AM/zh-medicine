<?php 
	namespace Home\Controller;
    use Home\Controller\CommonController;
	class PrescribeController extends CommonController{
		function index(){
		    if(IS_AJAX && IS_POST){
		        $ms=$this->fetch("patientCart");
		        $this->myreturn($ms,1);
		    }
		   
			
		}
		function showPrescribe(){
		    if(IS_POST && IS_AJAX){
		        $d=D('patient');
		        $map['card']=I('post.card');
		        $arr=$d->where($map)->select();
		        if(count($arr)==1){
		            session('patientid',$arr[0][id]);
		            session('doctorid',2);
		            $d=D('medicine');
		            $arr=$d->showMedicineB();
		            $this->assign('medicine',$arr);
		            $ms=$this->fetch('addPrescribe');
		            $this->myreturn($ms,1);
		        }else{
		            $this->myreturn('没有该用户');
		        }
		    }
		}
		/*
	    	@params:

	    	@return: 
		*/

		function addPrescribe(){  		
			//创建订单，向数据库处方表添加处方，并在对应的中间表添加记录
			//获取处方中的药品信息
			if(IS_POST && IS_AJAX){
    			$med=I('post.med');
    			for($i=0;$i<count($med);$i=$i+3){
    			    $m=[$med[$i],$med[$i+1],$med[$i+2]];
    			    $n[]=$m;
    			}
    			//开药总价格
    			$d=D('medicine');
    			for($i=0;$i<count($n);$i++){
    			    $arr=$d->getMerchantWayById(intval($n[$i][0]['value']));
    			   $spend+=intval($arr['sellprice'])*intval($n[$i][1]['value']); 
    			} 
    			$spend=$spend*intval(I('post.total'));
    			//数据库插入订单信息
    			$d=D('indent');
    			$time=date('Y-m-d H:i:s');
    			$arr=Array('ctime'=>$time,'patientid'=>session('patientid'),'doctorid'=>session('doctorid'),'iprice'=>$spend);
    			$flag=$d->add($arr);
    			$arr=$d->where(Array('ctime'=>$time))->select();
    			$iid=$arr[0]['id'];
    			//数据库插入处方信息
    			$d=D('prescribe');
    			$flag=$d->addPres(Array('diagnosis'=>I('post.diagnosis'),'iid'=>$iid,'num'=>I('post.total')));
    			//通过订单id找到处方id
    			$arr=$d->selectPidByIid(Array('iid'=>$iid));
    			$pid=$arr[0]['id'];
    			// 数据库插入药品处方中间信息
    			$d=D('medicine');
    			for($i=0;$i<count($n);$i++){
    			    $arr=$d->getMerchantWayById(intval($n[$i][0]['value']));
    			    $medid=intval($n[$i][0]['value']);
    			    $dosage=intval($n[$i][1]['value']);
    			    $remarks =$n[$i][2]['value'];
    			    $way=intval($arr['way']);
    			    $sellprice=$arr['sellprice'];
    			    $dd=D('medicine_prescribe');
    			    $dd->add(Array('pid'=>$pid,'medid'=>$medid,'dosage'=>$dosage,'way'=>$way,'remarks'=>$remarks,'sellprice'=>$sellprice));			    
    			}
    			$this->myreturn('ok',1);
		  }
		}

		/*
	    	@params:

	    	@return:
		*/
		function getHistoryPrescribe(){ 	
			//在数据库查找病人历史处方，展现在医生历史处方界面
			if(IS_POST && IS_AJAX){
                $d=D('indent');
                $arr=$d->where(Array('patientid'=>session('patientid')))->select();
                for($i=0;$i<count($arr);$i++){
                    $ctime=$arr[$i]['ctime'];
                    $tollid=$arr[$i]['tollid'];
                    $iid=$arr[$i]['id'];
                    if($tollid==null){
                        $spendstatus='未缴费';
                    }else{
                        $spendstatus='已缴费';
                    }
                    $d=D('prescribe');
                    $flag=$d->where(Array('iid'=>$iid))->select();
                    $diagnosis=$flag[0]['diagnosis'];
                    $num=$flag[0]['num'];
                    $sta=$flag[0]['status'];
                    $one=[$ctime,$diagnosis,$num,$sta,$spendstatus];
                    $all[]=$one;        
                }
                $this->myreturn($all,1);
              
    		}
            
		}



		/*
	    	@params:

	    	@return:
		*/
		function useHistoryPrescribe(){	//复用历史处方
            if(IS_POST && IS_AJAX){
                $d=D('indent');
                $arr=$d->where(Array('patientid'=>session('patientid'),'ctime'=>I('post.ctime')))->select();
                $iid=$arr[0]['id'];
                $d=D('prescribe');
                $arr=$d->selectPidByIid(Array('iid'=>$iid));
                $diagnosis=$arr[0]['diagnosis'];
                $num=$arr[0]['num'];
                $pid=$arr[0]['id'];
                $d=D('medicine_prescribe');
                $arr=$d->where(Array('pid'=>$pid))->field('medid,dosage,remarks')->select();
                $all=[$diagnosis,$num,$arr];
                $this->myreturn($all,1);
            }
		}




		/*
	    	@params:

	    	@return:
		*/
		function removeHistoryPrescribe(){  
			//医生撤销历史处方
            if(IS_POST && IS_AJAX){
                $d=D('indent');
                $arr=$d->where(Array('patientid'=>session('patientid'),'ctime'=>I('post.ctime')))->select();
                $tollid=$arr[0]['tollid'];
                if($tollid==null){
                    $iid=$arr[0]['id'];
                    $d=D('prescribe');
                    $arr=$d->where(Array('iid'=>$iid))->save(Array('status'=>'撤销'));
                    if($arr){
                        $this->myreturn('ok',1);
                    }         
                }else{
                    $this->myreturn('无法删除');
                }
            }
		}
	    
	}
?>