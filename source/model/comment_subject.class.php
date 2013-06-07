<?php
/**
 * Author: Lubo
 * Date: 24/12/11
 * Time: 14:33
 * Disc:
 * comment_subject是指可以被评论的单位，能被评论的所有对象都应该在comment的comment_type中，
 * 而所有subject对象都应该有comment_count属性，在被评论之后，comment中的post_insert会调用subject
 * 的add_comment_count方法
 */
interface CommentSubject{
    public function increase_comment_count();
}

?>