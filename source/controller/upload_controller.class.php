<?php
class UploadController extends Controller{

	protected static $require_admin = array();
	protected static $require_log_in = array();
	protected static $require_visitor = array();
	protected static $require_id = array();
	protected static $me_or_other = array();
	protected static $pag=array();
	
	
	function editor_img(){
		function success_html($name) {
			global $siteurl;
			$str="<script>window.parent.CKEDITOR.tools.callFunction($_GET[CKEditorFuncNum],'$siteurl/upload/editor_img/$name','上传成功!');</script>";
			exit ( $str );
		}

		$result=uploadimg ('upload',S_ROOT.'/upload/editor_img/');
		if(is_int($result)){
			if($result==-1)
			exit('<script>window.parent.alert("图片格式错误！")<script>');
			elseif($result==-2)
			exit('<script>window.parent.alert("图片过大！")<script>');
			elseif($result==-3)
			exit('<script>window.parent.alert("服务端错误！")<script>');
		}else{
			success_html($result);
		}
	}
}

?>