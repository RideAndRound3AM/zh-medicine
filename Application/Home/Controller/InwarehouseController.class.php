<?php
    namespace Home\Controller;
    use Home\Controller\CommonController;
    class InwarehouseController extends CommonController{
        function showInwarehouse(){         //展示所有入库单
            if(IS_AJAX){
                $inw=D("inwarehouse");
                $allInw=$inw->getAllInwarehouse();
                $this->assign("inwarehouse",$allInw);
                $this->myreturn($this->fetch("showInwarehouse"),1);
            }
        }
        
        function showInwarehouseDetails(){      //展示选中的入库单详情
            if(IS_AJAX && IS_POST){
                $inw=D("inwarehouse");
                $inwarehouseId=I("post.id");
                $inwarehouseDetails=$inw->getInwarehouseDetailsById($inwarehouseId);
                $this->assign("inwarehouse",$inwarehouseDetails);
                $this->myreturn($this->fetch("inwarehouseDetails"),1);
            }
        }
        
        function inwarehouse(){     //增加入库单
            if(IS_AJAX && IS_POST){
                $med=D("medicine");
                $medicine=$med->showMedicineA();
                $mer=D("merchant");
                $merchant=$mer->getAllMerchant();
                $this->assign("med",$medicine);
                $this->assign("mer",$merchant);
                $this->myreturn($this->fetch("buyDetails"),1);
            }
        }

        
        function showBuys(){        //展现未入库的采购单
            if(IS_AJAX && IS_POST){
                $inwarehouse=D("inwarehouse");
                $buyMedicine=D("medicine_buy");
                $buy=D("buy");
                $buyIds=$buyMedicine->where(array(status=>"已供货"))->select();//查找已供货药品的采购中间表
                $buyid=array();
                foreach($buyIds as $val){
                    $buyId[]=$val["bid"];
                }
                $id=array_unique($buyId);       //已经供货的采购单id
                $inwarehouseId=$inwarehouse->select();
                $inwarehouseBid=array();
                $inwarehouseDetails=array();
                $buys=array();
                foreach($inwarehouseId as $val){    //已入库的采购单id
                    $inwarehouseBid[]=$val["bid"];
                }
                
                foreach($id as $val){       //根据id判断已供货采购单是否入库，保存未入库采购单详情
                    if(!in_array($val,$inwarehouseBid)){
                        $inwarehouseDetails[]=$buy->getBuyById($val);
                    }
                }
                foreach($inwarehouseDetails as $val){
                    $buys[]=$val[0];
                }
                $this->assign("buys",$buys);
                $this->myreturn($this->fetch("buys"),1);
            }
        }
        
        function showBuyDetails(){      //查看采购单详情
            if(IS_AJAX && IS_POST){
                $buy=D("buy");
                $buyId=I("post.buyId");
                $buyInfo=$buy->getBuyDetailsById($buyId);
                $this->assign("buy",$buyInfo);
                $this->myreturn($this->fetch("allBuy"),1);
            }
        }
        
        function leadBuy(){     //导入选择的采购单
            $buyId=I("post.id");
            $buy=D("buy");
            $buyInfo=$buy->getBuyDetailsById($buyId);
            $this->assign("medicineInfo",$buyInfo);
            $this->myreturn($this->fetch("leadBuy"),1);
        }
        
        function affirmInwarehouse(){       //药品入库
            $inw=D("inwarehouse");
            $inwMed=D("medicine_inwarehouse");
            $inwarehouse=I("post.");
            $medicineInfo=$inwarehouse["medicineInfo"];
            $inwarehouseMedicine=array();
            $inwid=$inw->addInwarehouse($inwarehouse["bid"],$inwarehouse["invoice"],session("UserId"));
            for($i=0;$i<count($medicineInfo);$i+=5){
                $inwarehouseMedicine[$i/5]["batch"]=$medicineInfo[$i]["value"];
                $inwarehouseMedicine[$i/5]["medid"]=$medicineInfo[$i+1]["value"];
                $inwarehouseMedicine[$i/5]["buyprice"]=$medicineInfo[$i+2]["value"];
                $inwarehouseMedicine[$i/5]["realitynum"]=$medicineInfo[$i+3]["value"];
                $inwarehouseMedicine[$i/5]["merid"]=$medicineInfo[$i+4]["value"];
                $inwarehouseMedicine[$i/5]["inwid"]=$inwid;
            }
            $flag=$inwMed->addAll($inwarehouseMedicine);
            if($inwid && $flag){
                $this->myreturn("入库成功",1);
            }
        }
        
    }
?>