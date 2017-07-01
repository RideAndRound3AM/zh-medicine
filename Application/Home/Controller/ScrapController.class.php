<?php
    namespace Home\Controller;
    use Home\Controller\CommonController;
                
    class ScrapController extends CommonController{
        /*
         * 展示报废药品的界面
         */
        function showScrap(){
            if(IS_AJAX && IS_POST){
                $d=D('Medicine');
                $med=$d->showMedicineC();
                $this->assign('medicine',$med);
                $this->myreturn($this->fetch('scrap'),1);
            }else{
                $this->showLogin();
            }
        }
        /*
         * 添加删除药品的记录
         */
        function addScrap(){
            if(IS_AJAX && IS_POST){
                $arr=I('post.datas');
                for($i=0;$i<count($arr);$i++){
                    $time=time();
                    $arr[$i]['ctime']=date('y-m-d h:m:s',$time);
                    $arr[$i]['uid']=session('UserId');
                }
                $d=D('Scrap');
                $res=$d->addScrap($arr);
               if($res==1){
                   $this->myreturn($this->showScrap(),1);
               }else{
                   $this->myreturn($res);
               }
                
                
            }else{
                $this->showLogin();
            }

        }
    }

?>