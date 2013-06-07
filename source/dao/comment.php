<?php
define("DCNPL",5);
define("DCNPL2",4);
define("DCNPL3",3);

//////////////////////////////////////////
///评论相关的方法
/**
 * @brief 获取从某根节点开始的指定数量的评论的树,不包含根节点
 * @param $root 树的根节点元素或者其元素
 * @param $count array/int 当为int时,取出所有的节点,每个节点都有count个子节点
 * 当为array时,取出array里指定的每一层子节点数量
 * 如果最后一个数为0,则终止,否则取出所有层的节点,并且后面所有层的节点书全部和array中最后一位标示的子节点数量相同
 * @return 一个不含指定根节点的树,用数组的形式表示,每个数组表示的子节点的最后一个属性的子树
 */
function get_comment_tree($root, $count = array(DCNPL,DCNPL2,DCNPL3,0), $order_type = 'DESC') {
	$ret_tree = array ();
	//如果输入的count参数是int形式,则先转为array
	if (is_int ( $count ) || is_string ( $count )) {
		$count = array ($count );
	}
	
	if (is_array ( $count )) {
		//如果最后一个数为0,则
		if ($count [0] == 0) {
			return $ret_tree;
		} else { //根据$count的数量,取出后继的子树
			$root_id = 0;
			if (is_array ( $root )) {
				$root_id = $root ['id'];
			} elseif (is_int ( $root ) || is_string ( $root )) {
				$root_id = $root;
			} else {
				throw new Exception ( __FUNCTION__ . " param root should be int/string indicate root comment id or array indicate root element" );
			
			}
			//获取参数中指定的该子层comment的个数
			$comment_count = $count [0] < 0 ? "0" : $count [0];
			$ret_tree = get_element ( 'comment', '*', "commented_id='" . $root_id . "'", 'id ' . $order_type, $comment_count );
			if (empty ( $ret_tree )) {
				return NULL;
			} else {
				if (count ( $count ) > 1) {
					array_shift ( $count );
				}
				foreach ( $ret_tree as &$value ) {
					$value ['sub_comment'] = &get_comment_tree ( $value ['id'], $count );
				}
			}
			return $ret_tree;
		}
	} else {
		throw new Exception ( __FUNCTION__ . " param count should be int/string or array" );
	}

}

/**
 * @brief 获取弟弟节点的comment数组,根据brother的id和指定的排序方法
 *
 * @param$brother_id 可以是表示brother节点的数组,也可以是brother的id
 * @param$count 数组,表示每层子节点的个数
 * @param$order_type 和brother相对的排序方法,如果是asc,则取比brother后插入的comment
 *
 * @return comment数组
 */
function get_comment_sibling($brother, $count = array(DCNPL,DCNPL2,DCNPL3,0), $order_type = "DESC") {
	$ret_tree = array ();
	if (is_int ( $count ) || is_string ( $count )) {
		$count = array ($count );
	}
	if (is_array ( $count )) {
		if ($count [0] == 0) {
			return $ret_tree;
		} else {
			$brother_id = 0;
			if (is_array ( $brother )) {
				$brother_id = $brother ['id'];
			} elseif (is_int ( $brother ) || is_string ( $brother )) {
				$brother_id = $brother;
				$brother = get_element_by_key ( 'comment', 'id', $brother_id );
			} else {
				throw new Exception ( __FUNCTION__ . " param brother should be int/string indicate root comment id or array indicate root element" );
			}
			$comment_count = $count [0] < 0 ? "0" : $count [0];
			$where_sql = ($order_type == 'ASC') ? "id>'$brother_id' " : "id<'$brother_id' ";
			$where_sql .= "AND commented_id='" . $brother ['commented_id'] . "' ";

			$ret_tree = get_element ( 'comment', '*', $where_sql, 'id ' . $order_type, $count [0] );
			if (empty ( $ret_tree )) {
				return NULL;
			} else {
				if (count ( $count ) > 1) {
					array_shift ( $count );
				}
				foreach ( $ret_tree as &$value ) {
					$value ['sub_comment'] = &get_comment_tree ( $value, $count );
				}
			}
			return $ret_tree;
		}
	
	} else {
		throw new Exception ( __FUNCTION__ . " param count should be int/string or array" );
	}
}

function get_comment_of_user($user_id, $count = array(DCNPL,DCNPL2,DCNPL3,0)) {
	$ret_comments = get_element ( 'comment', '*', array ('user_id' => $user_id ), "id DESC", $count [0] );
	array_shift ( $count );
	foreach ( $ret_comments as &$comment ) {
		$comment ['sub_comment'] = get_comment_tree ( $comment, $count );
	}
	return $ret_comments;
}

function get_comment_sibling_of_user($brother, $count = array(DCNPL,DCNPL2,DCNPL3,0), $order_type = 'DESC') {
	$ret_tree = array ();
	if (is_int ( $count ) || is_string ( $count )) {
		$count = array ($count );
	}
	if (is_array ( $count )) {
		if ($count [0] == 0) {
			return $ret_tree;
		} else {
			$brother_id = 0;
			if (is_array ( $brother )) {
				$brother_id = $brother ['id'];
			} elseif (is_int ( $brother ) || is_string ( $brother )) {
				$brother_id = $brother;
				$brother = get_element_by_key ( 'comment', 'id', $brother_id );
			} else {
				throw new Exception ( __FUNCTION__ . " param brother should be int/string indicate root comment id or array indicate root element" );
			}
			$comment_count = $count [0] < 0 ? "0" : $count [0];
			
			$where_sql = ($order_type == 'ASC') ? "id>'$brother_id' " : "id<'$brother_id' ";
			$where_sql .= "AND user_id='" . $brother ['user_id'] . "'";
			$ret_tree = get_element ( 'comment', '*', $where_sql, 'id ' . $order_type, $count [0] );
			if (empty ( $ret_tree )) {
				return NULL;
			} else {
				if (count ( $count ) > 1) {
					array_shift ( $count );
				}
				foreach ( $ret_tree as &$value ) {
					$value ['sub_comment'] = &get_comment_tree ( $value, $count );
				}
			}
			return $ret_tree;
		}
	
	} else {
		throw new Exception ( __FUNCTION__ . " param count should be int/string or array" );
	}
}

function set_comment_unread($comment_id) {
	updatetable ( 'comment', array ('has_new_comment' => 'yes' ), "id='$comment_id'" );
	$comment = get_element_by_key ( 'comment', 'id', $comment_id );
	updatetable ( 'news_remind', array ('has_new_comment' => 'yes' ), "user_id='" . $comment ['user_id'] . "'" );
}

function set_comment_read($comment_id) {
	updatetable ( 'comment', array ('has_new_comment' => 'no' ), "id='$comment_id'" );
	$comment = get_element_by_key ( 'comment', 'id', $comment_id );
	updatetable ( 'news_remind', array ('has_new_comment' => 'no' ), "user_id='" . $comment ['user_id'] . "'" );
}
/**
 * 获取某个评论的未读评论
 * @param $comment_id
 */
function get_comment_unread($comment_id) {
	$ret_comment = get_element ( 'comment', "*", array ('commented_id' => $comment_id, 'has_been_read' => 'no' ) );
	return $ret_comment;
}

/**
 * 获取某评论评论的评论
 * @param $comment
 */
function get_comment_commented($comment) {
	$comment_id = 0;
	if (is_array ( $comment )) {
		$comment_id = $comment ['comment_id'];
	} elseif (is_int ( $comment ) || is_string ( $comment )) {
		$comment = get_element_by_key ( 'comment', 'id', $comment );
		$comment_id = $comment ['comment_id'];
	} else {
		throw new Exception ( '' );
	}
	return get_element_by_key ( 'comment', 'id', $comment_id );
}

/**
 * 获取评论所有未读的评论,包括用户自己的评论
 * @param $user_id
 */
function get_comment_unread_of_user($user_id) {
	$unread_comments_of_user = get_element ( 'comment', "*", "user_id='$user_id' AND has_new_comment='yes'", "id DESC" );
	foreach ( $unread_comments_of_user as &$comment ) {
		$comment ['sub_comment'] = get_comment_unread ( $comment ['id'] );
	}
	return $unread_comments_of_user;
}

/**
 * 创建评论
 * @status tested
 * @param $comment
 */
function create_comment($comment) {
	$comment ['create_time'] = time ();
	$comment ['is_delete'] = 'no';
	$comment ['has_been_read'] = 'no';
	set_comment_unread ( $comment ['subject_id'] );
	return inserttable( 'comment', $comment );
}
/**
 * 删除评论
 * @status tested
 * @param $id 评论id
 */
function delete_comment($id, $user_id) {
	$update_array = array ('is_delete' => 'yes', 'who_delete' => $user_id ,'delete_time'=>time());
	$comment=get_element_by_key('comment', 'id', $id);
	if ($comment['user_id']==$user_id) {
		$update_array['which_role_delete']='self';
	}else{
		$update_array['which_role_delete']='admin';
	}
	
	updatetable ( 'comment', $update_array, "id=$id" );
}

/**
 * 获取某主题的评论
 * @status tested
 * @param $subject_id 主题的id
 */
function get_comments($subject_id, $comment_type,$count = array(DCNPL,DCNPL2,DCNPL3,0), $order_type = "DESC") {
	//第一层的评论,floor_count为0
	$topic_comments = get_element ( 'comment', '*', array('comment_type'=>$comment_type,'subject_id'=>$subject_id,'floor_count'=>0), 'id ' . $order_type, $count [0] );
	//dump($topic_comments);
	if (empty ( $topic_comments )) {
		return NULL;
	} else {
		if (count ( $count ) > 1) {
			array_shift ( $count );
		}
		foreach ( $topic_comments as &$comment ) {
			$comment ['sub_comment'] = get_comment_tree ( $comment, $count, $order_type );
		}
	}
	return $topic_comments;
}

function get_comment($comment_id){
    return get_element_by_key('comment','id',$comment_id);
}
//
/////////////////////////////////
?>
