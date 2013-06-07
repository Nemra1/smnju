<?php
class IndexController extends Controller{
	protected static $require_admin = array();
	protected static $require_log_in = array();
	protected static $require_visitor = array();
	protected static $require_id = array();
	protected static $me_or_other = array();
	protected static $pag=array();

	function init(){
		$pag=array('news_pag');

	}

	function index(){
		$ret=$this->news_pag_html(1);
		register_smarty(array('news_list_content'=>$ret,'is_index_page'=>1));
	}

	function news_pag(){
		$ret=$this->news_pag_html($_GET['page_num']);
		echo $ret;
	}

	private function news_pag_html($cur_page){
		$pagination=new Pagination(2,$cur_page,5,site_url('/index/news_pag'),'#news_area');

		$news_list=$pagination->fetch_objects('News',array('news_role'=>'site'),'create_time desc');
		$news_list_html=generate_news_list_html($news_list);

		$html=$pagination->generate_html('news/pag.tpl',array('news_list_html'=>$news_list_html));
		return $html;
	}

	function student_wall(){
		global $_SC;
		$xml='';
		$xml.="<showData>";
		$users=User::get_objects(array('is_delete'=>'no'),'rand()',5,0);

		foreach ($users as $user){
			$xml.="<showDetail><path>{$_SC['siteurl']}/upload/avatar/{$user->avatar}</path>
			                   <description>我是{$user->alias_name}。{$user->description}
			                   </description></showDetail>
			       </showDetail>";
		}
		$xml.="</showData>";
		header("Content-type:text/plain;charset=utf-8");
		echo $xml;
	}






}
?>