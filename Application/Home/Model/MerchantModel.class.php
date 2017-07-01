<?php
namespace Home\Model;

use Think\Model\RelationModel;

class MerchantModel extends RelationModel{
    protected $_validate=array(
            array('name','','供货商名不能为空！',0,'notequal',1),
            array('name','','供货商已存在！',0,'unique',1),
            array('bankname','','开户银行不能为空！',0,'notequal',1),
            array('bankid','','银行账号不能为空！',0,'notequal',1)
        ); 
    /*
     * 倒序查看所有供货商信息
     */
    function getAllMerchant_desc(){
        $mer=$this->order('id desc')->page($_POST['p'],'10')->select();
        return $mer;
    }
    /*
     * 根据ID查看供货商
     */
    function getMerchantById(){
        $mer=$this->find(I('post.id'));
        return $mer;
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
     * 根据where条件查询供货商
     */
    function getMerchantByWhere(){
        $mer=$this->where(I('post.'))->select();
        return $mer;
    }
    /*
     * 获取所有供货商id，name
     */
    function getAllMerchant(){
        $mer=$this->field('id,name')->select();
        return $mer;
    }
    /*
     * 根据id查看供货商name
     */
    function getMerchantNameById($id){
        $mer=$this->field('name')->find($id);
        return $mer;
    }
}





?>