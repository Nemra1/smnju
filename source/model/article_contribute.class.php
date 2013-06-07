<?php
/**
 * This file is generate from table:
 * TABLE_NAME: article_rank_record
 * TABLE_COMMENT:
 */

class ArticleContribute extends Model {

	public  static $table_name='article_contribution';
	public  static $class_name='ArticleContribution';
	public  static $id_name='article_id';

	protected  static $translate=array('article_id' => 'article_id', 'ranking' => 'ranking', 'score' => 'score', 'reader' => 'reader');
	protected  static $require_not_null=array();
	protected  static $unique=array();
	protected  static $unchanged=array();
	protected  static $reg_validate=array();

	static  function init(){

	}


}

ArticleContribute::init();
