<?php
class ComplaintController extends Controller{
	
	protected static $require_admin = array();
    protected static $require_log_in = array();
    protected static $require_visitor = array();
    protected static $require_id = array();
    protected static $me_or_other = array();
    protected static $pag=array();
    
    
	function index(){
		register_smarty();
	}
}
?>