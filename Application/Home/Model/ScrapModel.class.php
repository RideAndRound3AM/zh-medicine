<?php
    namespace Home\Model;               
    use Think\Model\RelationModel;              
    class ScrapModel extends RelationModel{
        /*
         * 自动验证
         */
        protected $_validate=array(
            array('id','','药品不能为空！',0,'notequal',1),
            array('id','number','药品导入错误！'),
            array('warehouse','','数量不能为空！',0,'notequal',1),
            array('warehouse','number','数量输入错误！')
        );
        
        /*
         * 向报废表中添加数据
         */
        function addScrap($arr){
            /* if($this->create()){ */
                $this->addAll($arr);
                return 1;
           /*  }else{
                return $this->getError();
            } */
        }
        
        
        
    }
    

?>