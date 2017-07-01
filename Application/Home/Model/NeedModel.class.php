<?php
    namespace Home\Model;
    use Think\Model\RelationModel;
    class NeedModel extends RelationModel{
        
        /**
         * params:
         * {
         *      id:申领人id
         *       department:申领部门
         *      ctime"创建时间
         *      reason:驳回理由
         *      status:出货状态，默认未出货
         * }
         * return:
         */
        function addNeed($uid,$department){    //增加申领单
            $arr["uid"]=$uid;
            $time=time();
            $arr["ctime"]= date("y-m-d",$time);
            $arr["department"]=$department;
            $arr["status"]="未出货";
            $arr["reason"]="";
            return $this->add($arr);
        }
        /**
         * 根据申领表状态查看当前未出货的部门申领表
         * params:{
         *     status:部门申领单状态 
         * }
         * return：所有未出货的部门申领单
         */
        function getNeedBystatus($id){
            return $this->where(array(status=>"未出货"))->select();
        }
        
        /**
         * params:
         * {
         * $needId:申领单id
         * }
         * 
         * return：申领单详情
         * 
         */
        function getNeedDetails($needId){   //获取所有未出货的申领单药品
           return $res=$this->query("select t3.id,t3.name,t2.num,t3.standard from t_need as t1 join t_medicine_need as t2 on(t1.id=t2.nid)
                join t_medicine as t3 on(t2.medid=t3.id) where t1.id=%d and t2.status='未出货'",$needId);
        }
        protected $_link=array(
            "Medicine"=>array(
                "class_name"=>"Medicine",
                "mapping_calss"=>"medicines",
                "mapping_type"=>self::MANY_TO_MANY,
                "foreign_key"=>"nid",
                "relation_foreign_key"=>"medid",
                "relation_table"=>"t_medicine_need"
            )
        ); 
    }
?>