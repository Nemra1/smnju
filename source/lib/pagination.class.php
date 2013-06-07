<?php
class Pagination{
	private  $all_count;
	private $per_page;
	private $cur_page;
	private $show_num;
	private $pag_url;
	private $refresh_area;


	function __construct($per_page,$cur_page,$show_num,$pag_url,$refresh_area){
		$this->per_page=$per_page;
		$this->cur_page=$cur_page;
		$this->show_num=$show_num;
		$this->pag_url=$pag_url;
		$this->refresh_area=$refresh_area;
	}

	function fetch_objects($model,$where,$order_by){
		$objects=$model::get_objects($where,$order_by,$this->per_page,($this->cur_page-1)*$this->per_page);
		$this->all_count=$model::get_count($where);
		return $objects;
	}


	function generate_html($tpl,$array){
		$catlog_html=$this->get_catlog_pagination();
		$catlog_js="<script type='text/javascript'>
	    $(function(){
        $('.pag_link').click(function(){
            $.post($(this).attr('href'), function(data){
                $('{$this->refresh_area}').html(data);
            });
			return false;
        });
    })
    </script>";

		$array['pag_catlog']=$catlog_html;

		$ret=fetch_smarty($array,$tpl);

		$ret.=$catlog_js;

		return $ret;

	}


	private function get_catlog_pagination(){

		$all_count=$this->all_count;
		$per_page=$this->per_page;
		$page_no=$this->cur_page;
		$url=$this->pag_url;
		$link_count=$this->show_num;

		if($link_count%2==0){
			$link_count++;
		}
		$html="<div class='catlog_pagination'><ul class='fix_height'>";
		$all_page_num=ceil($all_count/$per_page);

		function append_link_html($index,$page_no,$url){
			$html='';
			if($index==$page_no)
			$html.="<li><a class='current_link pag_link' href='{$url}?page_num={$index}'>{$index}</a></li>";
			else
			$html.="<li><a class='pag_link' href='{$url}?page_num={$index}'>{$index}</a></li>";
			return $html;
		}

		function append_end_html($next_page_no,$end_page_no,$url){
			return "<li><a class='pag_link' href='{$url}?page_num={$next_page_no}'>下一页</a> <a  class='pag_link' href='{$url}?page_num={$end_page_no}'>末页</a></li>";
		}

		function append_start_html($last_page_no,$start_page_no,$url){
			return "<li><a class='pag_link' href='{$url}?page_num={$start_page_no}'>第一页</a> <a class='pag_link'  href='{$url}?page_num={$last_page_no}'>上一页</a></li>";
		}

		$half_link_count=floor($link_count/2);

		if($all_page_num==1){
			return '';
		}elseif($all_page_num<=$link_count){
			for($index=1;$index<=$all_page_num;$index++){
				$html.=append_link_html($index,$page_no,$url);
			}
		}elseif($page_no<=$link_count){
			for($index=1;$index<=$link_count;$index++){
				$html.=append_link_html($index,$page_no,$url);
			}
			$html.=append_end_html($link_count+1,$all_page_num-$half_link_count,$url);

		}elseif($page_no>$all_page_num-$link_count){
			$html.=append_start_html($all_page_num-$link_count,$half_link_count+1,$url);
			for($index=$all_page_num-$link_count+1;$index<=$all_page_num;$index++){
				$html.=append_link_html($index,$page_no,$url);
			}
		}elseif($page_no<=$all_page_num-$link_count&&$page_no>$link_count){
			$html.=append_start_html($page_no-$half_link_count-1,$half_link_count+1,$url);

			for($index=$page_no-$half_link_count;$index<=$page_no+$half_link_count;$index++){
				$html.=append_link_html($index,$page_no,$url);
			}
			$html.=append_end_html($page_no+$half_link_count+1,$all_page_num-$half_link_count,$url);

		}
		$html.="</ul></div>";
		return $html;
	}
}
?>