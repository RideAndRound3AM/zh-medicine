<?php
    namespace Home\Model;               
    use Think\Model\RelationModel;              
    class SalesreturnModel extends RelationModel{

        /*
         * 向退货表中添加数据
         */
        function addSalesreturn($arr){
                $a=$this->addAll($arr);
                return 1;
        }
        
        
        
    }
    

?>