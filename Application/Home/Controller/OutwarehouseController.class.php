<?php
    namespace Home\Controller;
    use Home\Controller\CommonController;
    class OutwarehouseController extends CommonController{
        /********************药房退还药库***************************************/
        /**
        *函数用途描述:药房药品退还药库页面
        *@date:2017年6月6日 上午9:58:53
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showMedBackStgPage(){
            if(IS_AJAX and IS_POST){
                $d=D('Medicine');
                $med=$d->showMedicined();
                $this->assign('medicineinfo',$med);
                $medicinebackstg=$this->fetch("medicinebackstg");
                $this->myreturn($medicinebackstg,1);
            }
        }
        /**
        *函数用途描述:确认退还药库
        *@date:2017年6月7日 上午10:45:33
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function confirmBackMed(){
            if(IS_AJAX && IS_POST){
                $arr=I('post.datas');
                
                /* $this->myreturn($arr,1); */
                $d=D('medicine');
                $res=$d->updateMedicineWP($arr);
                if($res==1){
                    $this->myreturn($this->showMedBackStgPage(),1);
                }else{
                    $this->myreturn($res);
                }
                 /* echo $res; */ 
               /*  $d=D('Scrap');
                $res=$d->addScrap($arr);
                if($res==1){
                    $this->myreturn($this->showScrap(),1);
                }else{
                    $this->myreturn($res);
                }  */
            
            
            }
        }
        /*********************出库*******************************************/
        /**
        *函数用途描述:药库药品出库页面
        *@date:2017年6月6日 上午9:59:50
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function showStgOutWhousePage(){
            if(IS_AJAX and IS_POST){
                $medicineouthouse=$this->fetch("medicineouthouse");
                $this->myreturn($medicineouthouse,1);
            }
        }
    }
?>