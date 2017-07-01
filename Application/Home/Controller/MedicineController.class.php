<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class MedicineController extends CommonController{
    /*
     * 倒序显示药品信息并分页
     */
    private function getHomeAndPage(){
        $d=D('medicine');
        $med=$d->getAllMedicine_desc();
        $this->assign("med",$med);
        $show=$d->getPage();
        $this->assign('page',$show);
    }
    /*
     * 药房人员查看药品的界面
     */
    function getMedicine_pharmacy(){
        if(IS_AJAX && IS_POST){
            $this->getHomeAndPage();
            $this->myreturn($this->fetch("pharmacy_medicine"),1);  
        }else{
            $this->showLogin();
        }
    }
    /*
     * 展示药品售价的界面
     */
    function getMedicine_sellprice(){
        if(IS_AJAX && IS_POST){
            $this->getHomeAndPage();
            $this->myreturn($this->fetch("sellprice_medicine"),1);
        }else{
            $this->showLogin();
        }
    }
    /*
     * 医生查看药品，修改药品的默认用量的界面
     */
    function getMedicine_doctor(){
        if(IS_AJAX && IS_POST){
            $this->getHomeAndPage();
            $this->myreturn($this->fetch("doctor_medicine"),1); 
        }else{
            $this->showLogin();
        }
    }
	
	/*
	 * 添加药品
		@params:
		{ name:药品名称
		  area:产地
		  sort:分类
		  standard:规格
		}
		@return:
		{ status:"1"
		}
		{ status:"0"
		  msg:"失败信息"
		}
	*/
	function addMedicine(){	
	    if(IS_AJAX && IS_POST){
    		$d=D("medicine");
    		if($d->create()){
    		    $d->add();
    		    $this->getMedicine_pharmacy();
    		}else{
    		    $this->myreturn($d->getError());
    		} 
	    }else{
	        $this->showLogin();
	    }
	}


	/*
	 * 修改药品信息
    	@params:
    	{  id:药品id
    	   area:产地
    	   sort:分类
    	}
    	@return:
    	{  status "ok"
    	}
    	{ status:"fail"
		  msg:"失败信息"
		}
	*/
	function updateMedicine(){ 
	    if(IS_AJAX && IS_POST){
            $d=D("medicine");
            $arr=$d->find(I('post.id'));
            if(!empty($arr)){
                $flo=$d->save(I('post.'));
                if($flo){
                    $this->myreturn('',1);
                }else{
                    $this->myreturn('信息修改失败!');
                }
            }else{
                $this->myreturn('药品不存在!');
            } 
	    }else{
	        $this->showLogin();
	    }
	}


         

	/*
	 * 根据ID获得药品信息
	 */
	function getMedicineById(){
	    if(IS_AJAX && IS_POST){
    	    $d=D("Medicine");
    	    $arr=$d->getMedicineById();
    	    if(!empty($arr)){
        	    $this->myreturn($d->getMedicineById(),1);
    	    }else{
        	    $this->myreturn('药品不存在!');
    	    }  
	    }else{
	        $this->showLogin();
	    }
	}
	/*
	 * 通用where条件展示页面及页码的方法
	 */
	private function commonWhere($html){
	    if(IS_AJAX && IS_POST){
	        $d=D("Medicine");
	        $med=$d->getMedicineByWhere();
	        if(!empty($med)){
	            $this->assign('med',$med);
	            $this->myreturn($this->fetch($html),1);
	        }else{
	            $this->myreturn('该药品不存在!');
	        }
	    }else{
	        $this->showLogin();
	    }
	}
	
	/*
	 * 药房人员根据where条件查药品信息
	 */
	function getMedicineByWhere_pharmacy(){
	    $this->commonWhere("pharmacy_medicine");
	}
	/*
	 * 医生根据where条件展示页面(上方注释部分添加则删除此项)
	 */
	function getMedicineByWhere_doctor(){
	    $this->commonWhere("doctor_medicine");
	}
	/*
	 * 药房人员根据where条件展示页面(上方注释部分添加则删除此项)
	 */
	function getMedicineByWhere_pharmacy_res(){
	    $this->commonWhere("pharmacy_reservoir");
	}
	/*
	 * 药库人员根据where条件展示页面(上方注释部分添加则删除此项)
	 */
	function getMedicineByWhere_warehouse_res(){
	    $this->commonWhere("warehouse_reservoir");
	}
	/*
	 * 修改药品售价时用的where搜索
	 */
	function getMedicineByWhere_sellprice(){
	    $this->commonWhere("sellprice_medicine");
	}
	
	

	/*
	 * 盘点、获取用户录入的实际库存，查找数据库库存，校正库存
    	@params:

    	@return:
	*/
	function clearMedicine(){    
	   

	}




	/*
    	@params:

    	@return:
	*/
	function setPharmacyMedicineCount(){    
		//修改药房药品数量，申领入库、退还药库时的操作

	}

	/*
	 * 展示设置药房药品高、低储
    	@params:

    	@return:
	*/
	function getPharmacy_res(){
	    if(IS_AJAX && IS_POST){
	        $this->getHomeAndPage();
	        $this->myreturn($this->fetch("pharmacy_reservoir"),1);
	    }else{
	        $this->showLogin();
	    }
	}
	/*
	 * 展示设置药库药品高低储
	 */
	function getWarehouse_res(){
	    if(IS_AJAX && IS_POST){
	        $this->getHomeAndPage();
	        $this->myreturn($this->fetch("warehouse_reservoir"),1);
	    }else{
	        $this->showLogin();
	    }
	}



}
?>