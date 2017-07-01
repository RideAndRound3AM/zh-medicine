<?php
    /* 订 单控制器：包含 缴费功能，配药，发药功能*/
    namespace Home\Controller;
    use Home\Controller\CommonController;
    /* 订单表控制器
     * 1，功能：  a，收费：通过卡号查看
     *        b，配药：查看所有已收费未发药的订单
     *        c，发药   ：通过卡号查看
     * 2，抓药人员状态 ，通过病人卡号来获取信息
     * 3,继承自CommonController
     * */
    class IndentController extends CommonController{
      /****************************************收费***************************************************************/
        /**
        *函数用途描述:收费页面入口
        *@date:2017年5月25日 下午3:52:57
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showTollPage(){
            if(IS_AJAX && IS_POST){
                $tollIndent=$this->fetch("tollIndent");
                $this->myreturn($tollIndent,1);
            }
        }
        
        
        /**
        *函数用途描述:展示待收费详情,已收费的订单不显示
        *@date:2017年5月18日 上午10:47:22
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function  showTollIndent(){ 
            if(IS_AJAX){
                $card=I("post.card");//得到病人卡号
                $d=D("Indent");
                $list=$d->getInfoByCard($card);
                 if($list){
                    $this->assign("list",$list);
                    $count=$d->getAllTollCount($card);
                    $show=$this->getPage($count,5);
                    $this->assign("page",$show); 
                    $indentBody=$this->fetch("indentBody");
                    $this->assign("indentBody",$indentBody);
                    $this->myreturn($indentBody,1);
                }else{
                    $this->myreturn("卡号不存在或没有缴费信息");
                } 
            }
        }
        
        
        /**
        *函数用途描述:确认收费函数
        *@date:2017年5月18日 下午10:31:47
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function confirmToll(){
            if(IS_AJAX){
                $indentid=I("post.indentid");//取到订单id
                $tollid=session('UserId');
                //session('tollid',array("tollid"=>3,"name"=>"杨收钱"));//本来要从session中取出收费人员id，这里自己设置一个，杨收钱
                $d=D("Indent")->updateTollIndent($indentid,$tollid);
                 if($d===1){//返回的是更新条数，一个订单对应一条信息
                    $this->myreturn($tollid,1);
                }else{
                    $this->myreturn("缴费失败!");
                } 
            }
        }
        /*************************************发药***************************************************/
        /**
        *函数用途描述:发药页面入口
        *@date:2017年5月25日 下午5:01:43
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showSeedMedicinePage(){
            if(IS_AJAX&&IS_POST){
                $queryMedicine=$this->fetch("queryMedicine");
                $this->myreturn($queryMedicine,1);
            }
        }
        
        /**
        *函数用途描述:展示待发药订单页面
        *@date:2017年5月19日 下午3:10:52
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showQueryMedicine(){
            if(IS_AJAX){
                $info=I("post.card");
                $d=D("Indent");
                $list=$d->getTakeMedicineByCard($info);
                if($list){
                    $this->assign("list",$list);
                    $count=$d->getTakeMedicineByCardCount($info);
                    $show=$this->getPage($count, 5);
                    $this->assign('page',$show);
                    $qMedicineBody=$this->fetch("qMedicineBody");
                    $this->assign("qMedicineBody",$qMedicineBody);
                    $this->myreturn($qMedicineBody,1);
                }else{
                    $this->myreturn("输入卡号错误或者已经取药！");
                }
            }
        }
        /**
        *函数用途描述:确认发药
        *@date:2017年6月5日 下午10:02:43
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function surePatientTakeMedicine(){
            if(IS_AJAX){
                $indentid=I("post.indentid");
                $d=D("Indent");
                $info=$d->updateTakeMedicineTime($indentid);
                if($info){
                    $this->myreturn($indentid,1);
                }else{
                    $this->myreturn("发药失败");
                }
            }
        }
        
        /*******************************************配药功能***************************************************************/
        /**
        *函数用途描述:配药页面入口
        *@date:2017年5月25日 下午4:59:33
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showAllTakePage(){
            if(IS_AJAX&&IS_POST){
                $allPayIndent=$this->fetch("allPayIndent");
                $this->myreturn($allPayIndent,1);
            }
        }
        /**
        *函数用途描述:展现所有收费的订单
        *@date:2017年5月24日 下午9:45:42
        *@author:Administrator
        *@param:variable
        *@return:
        */
         function showAllPayIndent(){
            if(IS_AJAX){
                $d=D("Indent");
                $list=$d->getAllPayIndent();
                if($list){
                    $this->assign("list",$list);
                    $count=$d->getAllCount();
                    $show=$this->getPage($count, 5);
                    $this->assign("page",$show);
                    $allPayBody=$this->fetch("allPayBody");
                    $this->assign("allPayBody",$allPayBody);
                    $this->myreturn($allPayBody,1);
                }else{
                    $this->myreturn('没有订单');
                } 
            }
        }
        /**
        *函数用途描述:展现处方
        *@date:2017年5月31日 下午4:15:58
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showCurrenIndentPrescribe(){
            if(IS_AJAX){
                $indentid=I("post.indentid");
                $d=D("Indent")->getPrescribeByIndentId($indentid);//记得修改
                if($d){
                    $this->assign('list_prescribe',$d);
                    $allPay_prescribe_body=$this->fetch('allPay_prescribe_body');
                    $this->assign('allPay_prescribe_body',$allPay_prescribe_body);
                    $this->myreturn($allPay_prescribe_body,1);
                }else{
                    $this->myreturn('没有相关处方');
                }
                
                $this->myreturn($d,1);
            }
        }
        /**
        *函数用途描述:展现药品
        *@date:2017年6月4日 下午8:56:17
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showCurrenPrescribeMedicine(){
            if(IS_AJAX){
                $prescribeid=I("post.prescribeid");
                $d=D("Indent");
                $list=$d->getMedicineInfoByPayPrescribe($prescribeid);
                if($list){
                    $this->assign('list_prescribe_medicine',$list);
                    $count=$d->getMedicineAllCount($prescribeid);
                    $show=$this->getPage($count,5);
                    $this->assign("page",$show);
                    $allPay_prescribe_medicine_body=$this->fetch('allPay_prescribe_medicine_body');
                    $this->assign('allPay_prescribe_medicine_body',$allPay_prescribe_medicine_body);
                    $this->myreturn($allPay_prescribe_medicine_body,1);
                }else{
                    $this->myreturn('没有相关药品！');                    
                }
            }
        }
        
        /**
        *函数用途描述:确认配药后，并移除该条数据
        *@date:2017年5月25日 下午9:33:23
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function sureTakeMedicine(){
            if(IS_AJAX){
                $indentid=I("post.indentid");
                $takeid=session('UserId');
                $d=D("Indent")->updateTakeMedicineIndent($indentid,$takeid);
                if($d){
                    $this->myreturn($d,1);
                }else{
                    $this->myreturn("取药失败");
                }
            }
        }
        
    }
                
?>