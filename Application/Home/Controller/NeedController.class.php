<?php
    namespace Home\Controller;
    use Home\Controller\CommonController;
    class NeedController extends CommonController{
        /**
         * 
         */
        function showDrawMedicine(){    //展现申领单
            if(IS_AJAX && IS_POST){
                $need=D("need");
                $needs=$need->select();
                $user=D("user");
                $userInfo=array();
                foreach($needs as $val){
                    $userInfo[]=$user->getUserById($val["uid"]);
                }
                for($i=0;$i<count($needs);$i++){
                    $needs[$i]["user"]=$userInfo[$i]["name"];
                }
                $this->assign("needs",$needs);
                $this->myreturn($this->fetch("showDrawMedicine"),1);
            }
        }
         function getNeedDetails(){         //查看申领单详情
             if(IS_AJAX && IS_POST){
                 $needId=I("post.needId");
                 $need=D("need");
                 $needMedicine=$need->relation(true)->where(array(id=>$needId))->select(); 
                 $num=$need->query("select num from t_medicine_need where nid=%d",$needId);
                 $res=$needMedicine[0]["Medicine"];
                 for($i=0;$i<count($num);$i++){
                     $res[$i]["num"]=$num[$i]["num"];
                 }
                 if($res){
                     $this->assign("medicine",$res);
                     $this->myreturn($this->fetch("needDetails"),1);
                 }
             }
         }
         
        /**
         * @param:
         * @return:     
         */
       function addDrawMedicine(){    //申领药品
           if(IS_AJAX && IS_POST){
               $d=D("medicine");
               $arr=$d->showMedicineA();
               $this->assign("medicine",$arr);
               if($arr){
                   $this->myreturn($this->fetch("drawMedicine"),1); 
               }
           }     
        } 
        /**
         * @param:
         * {
         *  @return:    
         * }
         */
        function leadWlowMedicine(){    //导入低储药品
            if(IS_AJAX){
                $med=D("medicine");
                $res=$med->getPlowMedicineForPharmacy();   //查询低储药品
                $allmedicine=$med->showMedicineA();
                if($res){
                    $this->assign("med",$res);
                    $this->assign("medicine",$allmedicine);
                    $this->myreturn($this->fetch("draw"),1);
                }else{
                    $this->myreturn("没有低储药品");
                }    
            }
        }
        function drawMedicine(){    //确认申领
            if(IS_AJAX){
                $need=D("need");
                $u=D("user");
                $needMedicine=D("medicine_need");
                $arr=I("post.med");
                $department=$u->getUserById()["department"];

                    $addToNeed=$need->addNeed(I("session.UserId"),$department);   //向申领表中添加记录
                    $res=array();           //申领药品的数组
                    for($i=0;$i<count($arr);$i+=2){
                        $res[$i/2]["medid"]=$arr[$i]["value"];
                        $res[$i/2]["num"]=$arr[$i+1]["value"];
                        $res[$i/2]["nid"]=$addToNeed;
                        $res[$i/2]["status"]="未出货";
                    }                    
                    $addToNeedMedicine=$needMedicine->addAll($res);  //向中间表添加记录
                        if($addToNeed && $addToNeedMedicine){
                            $this->myreturn("申领成功",1);    //添加成功
                            
                    }
                }
            }
            
            
        function showMedicineStandard(){    //输入药品时显示默认的规格
            $med=D("medicine");
            $res = I("post.");
            $arr=array_values($res);
                $msg["standard"]=$med->where(array(name=>$arr[0]))->select();
            if($msg["standard"]){
                $msg["status"]=1;
                $this->ajaxReturn($msg);
            }
        }
    }
?>