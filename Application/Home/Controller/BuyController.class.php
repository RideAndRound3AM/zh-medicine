<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class BuyController extends CommonController{
    function showBuy(){      //展现采购界面
        if(IS_AJAX && IS_POST){
            $buy=D("buy");
            $user=D("user");
            $buys=$buy->select();
            $widInfo=array();       //采购人
            $cidInfo=array();       //审批人
            foreach($buys as $val){
                $widInfo[]=$user->getUserById($val["wid"]);
            }
            for($i=0;$i<count($buys);$i++){
                $buys[$i]["wid"]=$widInfo[$i]["name"];
                $buys[$i]["cid"]=$cidInfo[$i]["name"];
            }
            $this->assign("buys",$buys);
            $this->myreturn($this->fetch("buys"),1);
        }
    }
    
    function getBuyDetails(){   //查看采购单详情
        if(IS_AJAX && IS_POST){
            $buyId=I("post.buyId");
            $buyMedicine=D("medicine_buy");
            $med=D("medicine");
            $mer=D("merchant");
            $medicine=array();      //保存药品名
            $merchant=array();      //保存供货商
            $buyDetails=$buyMedicine->where(array(bid=>$buyId))->select();
            foreach($buyDetails as $val){
                $medicine[]=$med->getMerchantWayById($val["medid"]);
                $merchant[]=$mer->getMerchantNameById($val["merid"]);
            }
            for($i=0;$i<count($buyDetails);$i++){
                $buyDetails[$i]["merchant"]=$merchant[$i]["name"];
                $buyDetails[$i]["medicine"]=$medicine[$i]["name"];
            }
            $this->assign("bid",$buyId);
            $this->assign("buy",$buyDetails);
            $this->myreturn($this->fetch("buyDetails"),1);
        }
    }
    
    function addBuy(){        //增加采购计划
        if(IS_AJAX &&IS_POST){
            $d=D("medicine");
            $arr=$d->showMedicineA();       //获取药品
            $mer=D("merchant");
            $res=$mer->getAllMerchant();    //获取供货商
            $this->assign("medicine",$arr);
            $this->assign("merchant",$res);
            $this->fetch("buy");
            if(!empty($arr)){     
                $this->myreturn($this->fetch("buy"),1);
            }
        } 
    }
    function showNeeds(){   //展现当前未出货的所有申领单
        if(IS_AJAX && IS_POST){
            $need=D("need");
            $needs=$need->getNeedBystatus();    //查询出所有未出货的申领单
            if(needs){
                $this->assign("needs",$needs);
                $this->myreturn($this->fetch("needs"),1);
            }      
        }
    }
    private function showNeedAssign(){    //根据ID获取申领单内容
        if(IS_AJAX){
            $needId=I("post.needId");
            $need=D("need");
            #$arr=$need->relation(true)->where(array(id=>$needId))->select();   //根据申领单id获取申领单中所有药品
            #var_dump($arr[0]);
            #$num=$need->query("select num from t_medicine_need where nid=$needId");//获取申领数量
            #$res=$arr[0]["Medicine"];
            #for($i=0;$i<count($num);$i++){
            #   $res[$i]["num"]=$num[$i]["num"];
            #}
            $res=$need->getNeedDetails($needId);        //获取申领单的详情
            if($res){
                $this->assign("needid",$needId);
                $this->assign("med",$res);
            }
        }      
    }
    function showNeed(){        //展现申领单详情
        $this->showNeedAssign();
         $this->myreturn($this->fetch("need"),1);
    }
    
    function loadNeed(){        //导入选择的申领单
         if(IS_AJAX){
             $this->showNeedAssign();
             $d=D('merchant');
             $merchant=$d->getAllMerchant();
             $this->assign('merchant',$merchant);
             $this->myreturn($this->fetch("needMedicine"),1);
            /* $medicineName=I("post.medicineName");
            $medicineNum=I("post.medicineNum");
            $medicineInfo=array();
            for($i=0;$i<count($medicineName);$i++){
                $medicineInfo[$i]["medicineName"]=$medicineName[$i];
                $medicineInfo[$i]["medicineNum"]=$medicineNum[$i];
            }
            $mer=D("merchant");
            $merchant=$mer->getAllMerchant();
            if($medicineInfo){
                $this->assign("needMedicine",$medicineInfo);
                $this->assign("merchant",$merchant);
                $this->myreturn($this->fetch("needMedicine"),1); 
            } */
        } 
    }
    
    function showHistoryBuy(){      //展现历史未供货药品 
        if(IS_AJAX){
            $buy=D("buy");
            $med=D("medicine");
            $medicines=$buy->getHistoryBuy();   //获取历史未供货药品
            $medicineId=array();
            $medicineName=array();
            foreach($medicines as $val){
                $medicineId[]=$val["medid"];
            };
            foreach($medicineId as $val){
                $medicineName[]=$med->getMedicineWarehouseById($val)["name"];   //根据id查询历史未供货药品名
            }
            for($i=0;$i<count($medicines);$i++){
                $medicines[$i]["name"]=$medicineName[$i];
            }
            if(!empty($medicines)){
                $this->assign("medicines",$medicines);
                $this->myreturn($this->fetch("historyBuy"),1);
            }      
        }
    }
    
    function loadBuy(){        //导入历史未供货药品
        if(IS_AJAX){
            $buy=D("buy");
            $medicines=$buy->getHistoryBuy();
            $medicineName=I("post.medicineName");
            $medicineNum=I("post.medicineNum");
            $medicineInfo=array();
            for($i=0;$i<count($medicineName);$i++){
                $medicineInfo[$i]["medicineId"]=$medicines[$i]["medid"];
                $medicineInfo[$i]["medicineName"]=$medicineName[$i];
                $medicineInfo[$i]["medicineNum"]=$medicineNum[$i];
            }
            $mer=D("merchant");
            $merchant=$mer->getAllMerchant();
            if($medicineInfo){
                $this->assign("buyMedicine",$medicineInfo);
                $this->assign("merchant",$merchant);
                $this->myreturn($this->fetch("buyMedicine"),1);
            }
        }
    }
    
    function affirmBuyPlan(){       //确认采购
        $med=D("medicine");
        $buy=D("buy");
        $buyMedicine=D("medicine_buy");
        $buyInfo=I("post.buyInfo");
        $addBuy=array();
        $bid=$buy->addBuyPlan(session("UserId"));
       for($i=0;$i<count($buyInfo);$i+=3){
            $addBuy[$i/3]["medid"]=$buyInfo[$i]["value"];
            $addBuy[$i/3]["buynum"]=$buyInfo[$i+1]["value"];
            $addBuy[$i/3]["merid"]=$buyInfo[$i+2]["value"];
            $addBuy[$i/3]["bid"]=$bid;
            $addBuy[$i/3]["status"]="未供货";
        }  
        $flag=$buyMedicine->addAll($addBuy);
        if($bid && $flag){
            $this->myreturn("添加成功",1);
        }
    }  

    function showExamineBuy(){      //展现未审批的采购单
        if(IS_AJAX && IS_POST){
            $buy=D("buy");
            $user=D("user");
            $username=array();
            $examineBuy=$buy->where(array(status=>"未审批"))->select();
            foreach($examineBuy as $val){
                $username[]=$user->getUserById($val["wid"]);
            }
            for($i=0;$i<count($examineBuy);$i++){
                $examineBuy[$i]["username"]=$username[$i]["name"];
            } 
            $this->assign("examine",$examineBuy);
            $this->myreturn($this->fetch("examineBuy"),1);
        }
    }
    
    function showBuyDetail(){
        if(IS_AJAX && IS_POST){
            $buyId=I("post.buyId");
            $buyMedicine=D("medicine_buy");
            $med=D("medicine");
            $mer=D("merchant");
            $medicine=array();      //保存药品名
            $merchant=array();      //保存供货商
            $buyDetails=$buyMedicine->where(array(bid=>$buyId))->select();
            foreach($buyDetails as $val){
                $medicine[]=$med->getMerchantWayById($val["medid"]);
                $merchant[]=$mer->getMerchantNameById($val["merid"]);
            }
            for($i=0;$i<count($buyDetails);$i++){
                $buyDetails[$i]["merchant"]=$merchant[$i]["name"];
                $buyDetails[$i]["medicine"]=$medicine[$i]["name"];
            }
            $this->assign("bid",$buyId);
            $this->assign("buy",$buyDetails);
            $this->myreturn($this->fetch("buyDetail"),1);
        }
    }
    
    function affirmExamineBuy(){    //确认审批
        if(IS_AJAX && IS_POST){
            $buy=D("buy");
            $arr=I("post.");
            $flag=$buy->save($arr);
            if($flag){
                $this->myreturn("审批成功",1);
            }
        }
    }
    
    function examineMedicine(){     //验收药品
        if(IS_AJAX && IS_POST){
            $medBuy=D("medicine_buy");
            $buy=D("buy");
            $buyId=I("post.bid");
            $medId=I("post.medid");
            $buyInfo=$buy->where(array(id=>$buyId))->select();
            $medicine=$medBuy->where(array(bid=>$buyId,medid=>$medId))->select();
            if($buyInfo[0]["status"]=="通过"){
                if($medicine[0]["status"]=="未供货"){
                    $medBuy->where(array(bid=>$buyId,medid=>$medId))->save(array(status=>"已供货"));
                    $this->myreturn("验收成功",1);
                }else{
                    $this->myreturn("该药品已经验收");
                }
            }else{
                $this->myreturn("请检查你验收的采购单是否审批合格");
            }
            
        }
    }
}
?>