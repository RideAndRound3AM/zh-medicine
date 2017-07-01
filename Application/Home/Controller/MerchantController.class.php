<?php
    namespace Home\Controller;
    use Home\Controller\CommonController;
    class MerchantController extends CommonController{
        /*
         * 生成页脚及home提替内容
         */
        private function getHomeAndPage(){
            $d=D('Merchant');
            $mer=$d->getAllMerchant_desc();
            $this->assign("mer",$mer);
            $show=$d->getPage();
            $this->assign('page',$show);
        }
        
        
        
        /*
         * 展示供货商页面
         */
        function getMerchant(){
            if(IS_AJAX && IS_POST){
                $this->getHomeAndPage();
                $this->myreturn($this->fetch("merchant"),1);
            }else{
                $this->showLogin();
            }    
        }
        /*
         * 新增供货商
         */
        function addMerchant(){
            if(IS_AJAX && IS_POST){
                $d=D("Merchant");
                if($d->create()){
                    $d->add();
                    $this->getMerchant();
                }else{
                    $this->myreturn($d->getError());
                }
            }else{
                $this->showLogin();
            } 
            
        }
        /*
         * 根据id查看供货商
         */
        function getMerchantById(){
            if(IS_AJAX && IS_POST){
                $d=D('Merchant');
                if(!empty($d->getMerchantById())){
                    $this->myreturn($d->getMerchantById(),1);
                }else{
                    $this->myreturn('供货商不存在');
                }
            }else{
                $this->showLogin();
            } 
        }
        /*
         * 修改供货商细信息
         */
        function updateMerchant(){
            if(IS_AJAX && IS_POST){
                $d=D("Merchant");
                $arr=$d->find(I('post.id'));
                if(!empty($arr)){
                    $flo=$d->save(I('post.'));
                    if($flo){
                        $this->myreturn('',1);
                    }else{
                        $this->myreturn('信息修改失败!');
                    }
                }else{
                    $this->myreturn('供货商不存在!');
                }
            }else{
                $this->showLogin();
            }
        }
        /*
         * 搜索供货商信息
         */
        function getMerchantByWhere(){
            if(IS_AJAX && IS_POST){
                $d=D("Merchant");
                $mer=$d->getMerchantByWhere();
                if(!empty($mer)){
                    $this->assign('mer',$mer);
                    $this->myreturn($this->fetch("merchant"),1);
                }else{
                    $this->myreturn('该供货商不存在!');
                }
            }else{
                $this->showLogin();
            }
        }
    }
?>