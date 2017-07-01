<?php
    namespace Home\Model;
    use Think\Model;
    class IndentModel extends Model{
        /**
        *函数用途描述:
        *@date:2017年5月19日 下午3:58:29
        *@author:Administrator
        *@param:variable
        *@return:
        */
        protected $_validata=array(
            
        );
    
  
        /**************************配药*********************************/
        /**
        *函数用途描述:通过订单id获取处方
        *@date:2017年6月4日 下午8:10:00
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function getPrescribeByIndentId($indentid){
            return $this->alias('t1')->field('t1.ctime,t2.diagnosis,t2.id as prescribeid,t2.num')
            ->join('t_prescribe as t2 on t1.id = t2.iid')
            ->where('t1.id=%d',$indentid)->select();
        }
        
        //获取订单总条数
        function getAllCount(){
            return $this->field('t1.id as indentid,t1.ctime,t2.name as pname,t2.card')->alias('t1')
            ->join('t_patient as t2 on t1.patientid=t2.id ')->order('t1.id desc')
            ->where("t1.tollid is not null and t1.takeid is null")->count();
        }
        
        //获取所有订单
        function getAllPayIndent(){
            return $this->field('t1.id as indentid,t1.ctime,t2.name as pname,t2.card')->alias('t1')
            ->join('t_patient as t2 on t1.patientid=t2.id ')->page($_POST['p'],'5')->order('t1.id desc')
            ->where("t1.tollid is not null and t1.takeid is null")->select();
        }
        /**
        *函数用途描述:通过处方id查看药品
        *@date:2017年6月4日 下午8:48:23
        *@author:Administrator
        *@param:variable
        *@return:
        */      
        function getMedicineInfoByPayPrescribe($prescribeid){
            return $this->table('t_medicine as t1')->field('t1.name as medname,t2.dosage,t3.id as prescribeid')
            ->page($_POST['p'],'5')
            ->join('t_medicine_prescribe as t2 on t1.id=t2.medid')
            ->join('t_prescribe as t3 on t2.pid=t3.id')
            ->where('t3.id=%d',$prescribeid)->select();
        }
        /**
        *函数用途描述:通过处方id获取药品总条数
        *@date:2017年6月5日 上午1:01:03
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function getMedicineAllCount($prescribeid){
            return $this->table('t_medicine as t1')->field('t1.name as medname,t2.dosage,t3.id as prescribeid')
            ->join('t_medicine_prescribe as t2 on t1.id=t2.medid')
            ->join('t_prescribe as t3 on t2.pid=t3.id')
            ->where('t3.id=%d',$prescribeid)->count();
        }
        /**
        *函数用途描述:确认配药
        *@date:2017年6月5日 上午12:13:45
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function updateTakeMedicineIndent($indentid,$takeid){
            $arr=$this->find($indentid);
            if(!empty($arr)){
                $arr['takeid']=$takeid;
                return  $this->save($arr);
            }else{
                return false;
            }
        }
    /**************************发药页面******************************************/
        
        /**
        *函数用途描述:根据病人卡号获取订单信息(发药页面)
        *         也可以通过订单获取病人信息
        *         通过合并两条查询的方式，可以获得医生和开单人员的名字
        *@date:2017年5月18日 上午10:57:20
        *@author:Administrator
        *@param:$card:传入病人卡号
        *@return:
        */
        function getTakeMedicineByCard($card){
            return  $this->alias('t1')->field('t1.id as indentid,t2.name as pname,t2.sex,t1.ctime,t2.card')
            ->join('t_patient as t2 on t1.patientid=t2.id')->page($_POST['p'],'5') 
            ->where('t1.tollid is not null and t1.takeid is not null and t1.taketime is null and t2.card=%d',$card)
            ->select();
        }
        /**
        *函数用途描述:获取发药信息总条数
        *@date:2017年6月5日 下午3:33:34
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function getTakeMedicineByCardCount($card){
            return  $this->alias('t1')->field('t1.id as indentid,t2.name as pname,t2.sex,t1.ctime')
            ->join('t_patient as t2 on t1.patientid=t2.id')
            ->where('t1.tollid is not null and t1.takeid is not null and t1.taketime is null and t2.card=%d',$card)
            ->count();
        }
        /**
        *函数用途描述:确认发药
        *@date:2017年6月5日 下午10:12:42
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function updateTakeMedicineTime($indentid){
            $arr=$this->find($indentid);
            if(!empty($arr)){
                $now_time = date('Y-m-d H:i:s',time());
                $arr['taketime']=$now_time;
                return  $this->save($arr);
            }else{
                return false;
            }
        }
      /**************************收费页面**********************************************/ 
        /**
        *函数用途描述:通过病人卡号获取订单信息，已收费的订单不查出来(收费页面)
        *@date:2017年5月18日 下午3:54:51
        *@author:Administrator
        *@param:注意设置别名，病人的名字和医生的名字字段都叫做name，把病人的name叫做pname
        *取到 病人名字，病人卡号，订单价格，医生名字
        *@return:pname,card,iprice,indentid
        */
        function getInfoByCard($card){//判断字段为空要用 is null 
             return $this->alias('t1')
            ->field('t1.id as indentid,t1.iprice,t2.name as pname,t2.card')
            ->join('t_patient as t2 on t1.patientid=t2.id')->page($_POST['p'],'5')  
            ->where('t1.tollid is null and t2.card=%d',$card)
            ->select(); 
        }
        /**
        *函数用途描述:获取收费总条数
        *@date:2017年6月5日 上午9:51:45
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function getAllTollCount($card){
            return $this->alias('t1')
            ->field('t1.id as indentid,t1.iprice,t2.name as pname,t2.card')
            ->join('t_patient as t2 on t1.patientid=t2.id')
            ->where('t1.tollid is null and t2.card=%d',$card)
            ->count();
        }
        
        /**
        *函数用途描述:确认收费，更改数据库(收费人员名字在session中获取，不用在数据库中查询了，提高性能)
        *注意：save方法的返回值是影响的记录数，如果返回false则表示更新出错，因此一定要用恒等来判断是否更新失败。
        *@date:2017年5月18日 下午10:35:02
        *@author:Administrator
        *@param:$indentId：是订单id     $tollid是指从session中获取到的收费人员id
        *
        *@return:
        */
        function updateTollIndent($indentid,$tollid){
            //return $this->where('id=$indentid')->save('tollid=$tollid');
             $arr=$this->find($indentid);
            if(!empty($arr)){
                $arr['tollid']=$tollid;
                $now_time = date('Y-m-d H:i:s',time());
                $arr['tolltime']=$now_time;
               return  $this->save($arr);
            }else{
               return false; 
            } 
        }
        
        /**
        *函数用途描述:根据订单获取相应药品
        *@date:2017年5月25日 上午10:07:38
        *@author:Administrator
        *@param:variable
        *@return:
        */
        function getMedcineInfoByIndent($indentid){
            return $this->query("SELECT t1.id as indentid,t4.`name` as medname,t4.id as medid,t3.dosage from t_indent as t1 join
                t_prescribe as t2 ON(t1.id=t2.iid) JOIN t_medicine_prescribe as t3 on(t3.pid=t2.id)
                JOIN t_medicine as t4 on(t3.medid=t4.id) where t1.id = %d",$indentid);
        }
    }
                
?>