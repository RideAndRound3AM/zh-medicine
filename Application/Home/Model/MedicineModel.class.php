<?php
    namespace Home\Model;
    use Think\Model\RelationModel;            
    class MedicineModel extends RelationModel{
        /*
         *根据药品查看供货商   
         */
        
        protected $_link=array(
            "Merchant"=>array(
                "mapping_type"=>self::MANY_TO_MANY,
                "foreign_key"=>"medid",
                "relation_foreign_key"=>"merid",
                "relation_table"=>"t_medicine_merchant"
            )  
        );
        /*
         * 设置自动验证
         */
         protected $_validate=array(
            array('name','','药品名不能为空！',0,'notequal',1),
            array('name','','药品已存在！',0,'unique',1),
            array('area','','药品产地不能为空！',0,'notequal',1),
            array('sort','','药品分类不能为空！',0,'notequal',1),
            array('standard','','药品规格不能为空！',0,'notequal',1)
        ); 
         /*
          * 倒序获取10条药品信息
          */
        function getAllMedicine_desc(){
            $med=$this->order('id desc')->page($_POST['p'],'10')->select();
            return $med;
        }
        /*
         * 根据id获取药品信息
         */
        function getMedicineById(){
            $med=$this->find(I('post.id'));
            return $med;
        }
        /*
         * 根据where条件获取药品信息
         */
        function getMedicineByWhere(){
            $med=$this->where($_POST)->select();
            return $med;
        }
        /*
         * 自动生成页脚
         */
        function getPage(){
            $count=$this->count();
            $Page=new \Home\Common\MyPage($count,10);
            $show=$Page->show();
            return $show;
        }
        /*
         * 获取所有药品id和name
         * return 二维数组
         */
        function showMedicineA(){
            $med=$this->field('id,name')->select();
            return $med;
        }
        /*
         * 获取所有药品id,name,way,dosage,sellprice
         */
        function showMedicineB(){
            $med=$this->field('id,name,way,dosage,sellprice')->select();
            return $med;
        }
        /*
         * 获取所有药品id,name,standard,warehouse
         */
        function showMedicineC(){
            $med=$this->field('id,name,standard,warehouse')->select();
            return $med;
        }
        /*
         * 获取所有药品id,name,standard,pharmacy
         */
        function showMedicineD(){
            $med=$this->field('id,name,standard,pharmacy')->select();
            return $med;
        }
        /*
         * 获取药房所有数量低于药品低储的药品
         */
        function getPlowMedicineForPharmacy(){
            $med=$this->where('pharmacy<plow')->field('id,name,standard')->select();
            return $med;
        }
        /*
         * 根据药品id查看药品库存
         */
        function getMedicineWarehouseById($id){
            $med=$this->field('name,warehouse')->find($id);
            return $med;
        }
        /*
         * 根据药品id查name,sellprice,way
         */
        function getMerchantWayById($id){
            $med=$this->field('name,sellprice,way')->find($id);
            return $med;
        }
        /*
         * 药房退还药品至药库
         */
        function updateMedicineWP($arr){
             $arr1=array(); 
            for($i=0;$i<count($arr);$i++){
                 $d=$this->field('warehouse,pharmacy')->find($arr[$i]['id']);
                 $arr2['id']=$arr[$i]['id']*1;
                 $arr2['warehouse']=$d['warehouse']*1+$arr[$i]['updatenum']*1;
                 $arr2['pharmacy']=$d['pharmacy']*1-$arr[$i]['updatenum']*1;
                 $res=$this->save($arr2);
                 $arr1[]=$res;
            }
            $br=in_array(false,$arr1);
             if(!$br){
                return 1;
            }else{
                return '数据修改失败或部分失败';
            }  
        }
    }




?>