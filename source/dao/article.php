<?php
function create_article($article) {
	insert_table_with_preprocess('article',$article);
}
/**
 * @brief 一般用户删除发布的文章,删除前首先要检查是否是参与投稿的文章,如果是投稿的文章,则
 * 不能删除,要用户联系管理员
    *
    * @param $article_id 要删除文章的id
    *
    * @return 1表示删除成功,0表示要联系管理员
 */
function delete_article($article_id) {
    get_element_by_key('');
	updatetable('article',array('is_delete'=>'yes'),array('id'=>$article_id)); 
}
function delete_article_admin($article_id){
    
}
function contribute_article($article_id){
    inserttable('article_contribute',array('article_id'=>$article_id),0);
}
function score_article(){
	
}

?>
