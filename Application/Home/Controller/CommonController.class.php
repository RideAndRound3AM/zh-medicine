<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller{
    
    /*
     * 用于返回数据的固定格式函数
     */
    protected function myreturn($content='',$status=0){
        $msg=array('status'=>$status,'content'=>$content);
        $this->ajaxReturn($msg);
    }

    /*
     * 跳转到登录界面
     */
    protected function showLogin(){
        $this->display("public/error");
    }
    /*
     * 分页方法
     */
    protected function getPage($count,$pagenum){
        $Page=new \Home\Common\MyPage($count,$pagenum);
        $show=$Page->show();
        return $show;
    }
    public function _empty(){
        $this->showLogin();
    }
    
    
}
?>