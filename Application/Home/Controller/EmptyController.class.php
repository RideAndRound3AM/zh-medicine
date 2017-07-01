<?php
    namespace Home\Controller;
    use Home\Controller\CommonController;           
    class EmptyController extends CommonController{
        function index(){
            $this->showLogin();
        }
    }

?>