<?php 
    namespace Home\Model;
    use Think\Model\RelationModel;
    Class UserModel extends RelationModel{
        function getRightByUserId($UserId){
            //通过查right这个表查出所对应的功能权限
            $arr=$this->query(' select t4.rights,t4.name from t_user as t1 join
 			        t_user_role as t2 on(t1.id=t2.uid) join
 			        t_role_right as t3 on(t2.rid=t3.rid)
 			        join t_right as t4 on(t3.rigid=t4.id) where
                    t1.id=%d', $UserId);
            //返回出来数组
            return $arr;
        }
        
        
        function getUserByWhere(){
            //通过用户名密码来查出 所对应的所有信息
           $val= $this->where(array('name'=>I('post.name'),
            'password'=>I('post.password')))->select();
            return $val;
        }
        /*
         * 根据用户ID查看用户姓名和部门
         */
       function getUserById($Id){
           $val = $this->field("name,department")->find($Id);
           return $val;
       }
       /*
        * 根据用户id产看用户角色
        */
       function getRoleById(){
           $val = $this->query('select t3.role,t3.id from t_user as t1 join t_user_role as t2 on(t1.id=t2.uid)
               join t_role as t3 on(t2.rid=t3.id) where t1.id=%d',session('UserId'));
           return $val;
           
       }
       /*
        * 根据角色查权限
        */
       function getRightsById($id){
           header("Content-type: text/html;charset=utf-8");
           $val = $this->query('select t3.rights,t3.name  from t_role as t1 join t_role_right as t2 on(t1.id=t2.rid)
               join t_right as t3 on(t2.rigid=t3.id) where t1.id=%d',$id);
            return $val; 
       }
       /*
        * 根据用户id获取用户职位和权限
        */
       function getRoleAndRight(){
           $res=$this->getRoleById();
           $arr=array();
           for($i=0;$i<count($res);$i++){
               $arr1['role']=$res[$i]['role'];
               $rights=$this->getRightsById($res[$i]['id']);
               $arr1['rights']=$rights;
               $arr[]=$arr1; 
           } 
            return $arr; 
       }
    }
?>
