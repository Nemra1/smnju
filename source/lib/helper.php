<?php

function generate_news_list_html($news_list){
	$news_list_html='';
	$news_list=Model::snormalize($news_list);
	
	foreach($news_list as $k=>$v){
		$tpl_array_json=json_decode(format_json($v->tpl_array_json),true);
		$tpl_name='news/'.$v->news_type.'_news.tpl';
		$ret=fetch_smarty($tpl_array_json,$tpl_name);
		$news_list_html.=$ret;
	}
	
	return $news_list_html;
	
}

?>