<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class SalesreturnController extends CommonController{
    /*
     * 展示退还药品页面
     */
    function getSalesreturn(){
        if(IS_AJAX && IS_POST){
            $d=D('Medicine');
            $med=$d->showMedicineC();
            $this->assign('medicine',$med);
            $e=D('Merchant');
            $mer=$e->getAllMerchant();
            $this->assign('merchant',$mer);
            $this->myreturn($this->fetch('salesreturn'),1);
        }else{
            $this->showLogin();
        }
    }
    /*
     * 添加退货药品的记录
     */
    function addSalesreturn(){
        if(IS_AJAX && IS_POST){
            $arr=I('post.datas');
            for($i=0;$i<count($arr);$i++){
                $time=time();
                $arr[$i]['ctime']=date('y-m-d h:m:s',$time);
            }
            $d=D('Salesreturn');
            $res=$d->addSalesreturn($arr);
            if($res==1){
                $this->myreturn($this->getSalesreturn(),1);
            }else{
                $this->myreturn($res);
            }
    
    
        }else{
            $this->showLogin();
        }
    
    }
}


?>