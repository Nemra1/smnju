<?php
/////////////////////////////////
///主题相关的方法


/**
 * 创建主题
 * @status tested
 * @param  $topic
 */
function create_topic($topic) {
	return inserttable( 'topic', $topic );
}

/** 
 * 删除主题
 * @status tested
 * @param  $id
 */
function delete_topic($id,$user_id) {
	update_topic ( array ('id' => $id, 'is_delete' => 'yes','who_delete'=>$user_id ) );
}

/**
 * 更新主题
 * @status tested
 * @param  $topic
 */
function update_topic($topic) {
	updatetable('topic', $topic, "id='$topic[id]'");
}
/**
 * 获取所有主题
 * @status tested
 */
function get_topics($limit, $start = 0) {
	$topic_array = get_element ( 'topic', '*', "is_delete='no'", "id", $limit, $start, "id" );
	return $topic_array;
}

function get_topic($id) {
	return get_element_by_key ( 'topic', 'id', $id );
}
///
///////////////////////////////////

?>
