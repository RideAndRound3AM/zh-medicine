<?php
namespace Home\Model;
use Think\Model\RelationModel;
class BuyModel extends RelationModel{
	/**
	 * @param:
	 * {
	 *     采购单id
	 * }
	 * @return:
	 * {
	 * 
	 * }
	 */
	function getBuyById($id){  //根据采购单id查看采购单
        return $this->where(array("id"=>$id))->select();
    }

    
    /**
     * @param:
     */
    function getHistoryBuy(){   //获取历史未供货采购药品
        return $this->query("select * from t_medicine_buy where status='未供货'");
    } 
    
    /**
     * @param {
     *        $wid:采购单编辑用户      
     *         }
     * @return ：添加成功返回id
     */
    function addBuyPlan($wid){
        $time=time();
        $arr["ctime"]=date("y-m-d",$time);
        $arr["status"]="未审批";
        $arr["cid"]=null;
        $arr["wid"]=$wid;
        return $this->add($arr);
    }
    
    /**
     * params:
     * {
     * $id:采购单ID
     * }
     * @return: 采购单详情
     * 
     */
    function getBuyDetailsById($id){
        return $this->query("select t4.name as medname,t4.id as medid,t3.id as merid,t3.name as mername,t2.bid,t2.status,t2.buynum from t_buy as t1 join t_medicine_buy as t2 on(t1.id=t2.bid) join t_merchant as t3 on(t2.merid=t3.id) join t_medicine as t4 on(t2.medid=t4.id) where t1.id=%d",$id);
    }
   
	protected $_link=array(
		"Buy"=>array(    
			"class_name"=>"buy",
			"maping_name"=>"buy",
			"maping_type"=>"BELONGS_TO",
		    "relation_foreign_key"=>"bid",
		    "relation_table"=>"t_medicine_buy"
			)
		);
    }
?>