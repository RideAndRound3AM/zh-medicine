<?php
namespace Home\Model;
use Think\Model\RelationModel;
class InwarehouseModel extends RelationModel{
    /**
     * params:
     * 
     * return:入库单详情
     */
    function getAllInwarehouse(){       //查看所有入库单
        return $this->query("select t1.id,t1.ctime,t1.invoice,t1.bid,t3.name from t_inwarehouse as t1 join t_buy as t2 on(t1.bid=t2.id) join t_user as t3 on(t1.uid=t3.id)");
    }

    /**
     * params:
     * {
     * $id:入库单id
     * }
     * 
     * return:
     * $arr:入库单详情
     */
    function getInwarehouseDetailsById($id){        //根据ID查看输入的入库单详情
        return $arr=$this->query("select t3.name as medname,t2.buyprice,t2.realitynum,t4.name as mername,t2.batch from t_inwarehouse as t1 join t_medicine_inwarehouse as t2 on(t1.id=t2.inwid) join t_medicine as t3 on(t2.medid=t3.id) join t_merchant as t4 on(t2.merid=t4.id) where t1.id=%d",$id);
    }
    
    /**
     * params:
     * {
     *      
     * }
     * 
     * 
     */
    function addInwarehouse($bid,$invoice,$uid){
        $time=time();
        $arr["ctime"]=date("y-m-d",$time);
        $arr["bid"]=$bid;
        $arr["invoice"]=$invoice;
        $arr["uid"]=$uid;
        return $this->add($arr);
    }
    
}
?>