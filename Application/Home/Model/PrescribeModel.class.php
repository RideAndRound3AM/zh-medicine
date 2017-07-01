<?php
    namespace Home\Model;
    use Think\Model\RelationModel;
    class PrescribeModel extends RelationModel{
        protected $_link=array(
            "Medicine_prescribe"=>array(
                "mapping_type"=>self::HAS_MANY,
                "foreign_key"=>"pid"
            )
        );
        //增加处方
        function addPres($m){
            $mes=$this->add($m);
            return $mes;
        }
        //通过订单id查询处方id
        function selectPidByIid($m){
            $mes=$this->where($m)->select();
            return $mes;
        }
        
    }
?>