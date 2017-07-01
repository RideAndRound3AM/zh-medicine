<?php
	namespace Home\Controller;
	use Home\Controller\CommonController;
	class IndexController extends CommonController{
	    /*
	     * 展现登录界面
	     */
		function index(){
		    if (IS_GET){
		        $this->display('login');
		    }else{
		       $this->showLogin();
		    }
		}
		/*
		 * 进入工作页面
		 */
		
		function login(){
		    //自动校验
		    //如果是POST请求 就进入
		    if(IS_POST){
		        $d=D("user");
		        $val =$d->getUserByWhere();
		        if(count($val)==1){
		            $id=$val[0]['id'];
		            session("UserId",$id);
		            $arr=$d->getRoleAndRight();
		            $this->assign('name',$val[0]['name']);
		            $this->assign('list',$arr);
		            $imagediv=$this->fetch('Index/image');
		            $this->assign('body',$imagediv);
		            $this->display("public/header");
		        }else{
		            $this->display("login");
		        }
		    }else{
		        $this->showLogin();
		    }
	}
	/*
	   退出登录 
	   */
	
	function signOut(){
	    unset($_SESSION["UserId"]);
	    $this->myreturn('',1); 
	}
	}
?>