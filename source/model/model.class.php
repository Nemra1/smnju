<?php
abstract class Model
{

	protected static $table_name = NULL;
	protected static $class_name = NULL;
	protected static $id_name = 'id';

	protected static $translate = array();
	protected static $require_not_null=array();
	protected static $unique = array();
	protected static $unchanged = array();
	protected static $reg_validate = array();

	public $attrs = array();
	protected $is_normalized = false;
	protected $error_msg = '';


	protected function __construct($attrs = array())
	{
		$this->attrs = $attrs;
	}

	/**
	 *
	 * @param $name
	 * @return NULL if attribute not found in attrs list
	 */
	public function __get($name)
	{
		return $this->get($name);
	}

	/**
	 *
	 * @param $name
	 * @param $value
	 * @return false if set failed.
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	public function __isset($name)
	{
		return isset($this->attrs[$name]);
	}

	public function __unset($name)
	{
		unset($this->attrs[$name]);
	}


	public function get($name)
	{
		if (isset($this->attrs[$name]))
		return $this->attrs[$name];
		else
		return NULL;
	}

	public function set($name, $value)
	{
		if ($name == static::$id_name)
		return false;

		if ($this->has_id() && in_array($name, static::$unchanged) && ($this->attrs[$name] !== NULL))
		return false;
		else
		$this->attrs[$name] = $value;

	}


	public function has_id()
	{
		return isset($this->attrs[static::$id_name]);
	}




	public function merge_attrs($attrs)
	{
		$o_attrs = $this->attrs;
		$ret = true;
		foreach ($attrs as $k => $v) {
			$ret = $this->set($k, $v);
			if ($ret === false) {
				$this->attrs = $o_attrs;
				return false;
			}

		}
	}

	public function get_attrs()
	{
		return $this->attrs;
	}


	protected static function add_reg_validate($name, $reg, $notice = NULL)
	{
		if ($notice === NULL)
		$notice = static::$translate[$name] . '格式错误';

		static::$reg_validate[$name] = array('reg' => $reg, 'notice' => $notice);
	}


	/**
	 * If return false, you can see error_msg() for error info.
	 * @return false if invalid.
	 */
	public function validate()
	{

		foreach (static::$require_not_null as $v) {
			if (isset($this->attrs[$v]) && trim($this->attrs[$v]) === '') {
				if (empty(static::$translate[$v]))
				$translate = $v;
				else
				$translate = static::$translate[$v];

				$this->error_msg = $translate . '为空！';
				return false;
			}
		}

		foreach (static::$unique as $v) {
			if (!isset($this->attrs[$v]))
			continue;

			if ($this->has_id()) {
				$id = $this->get_id();
				$id_name = static::$id_name;
				$ret = check_if_exist(static::$table_name, "$v='{$this->attrs[$v]}' and {$id_name}<>'{$id}'");
			} else {
				$ret = check_if_exist(static::$table_name, "$v='{$this->attrs[$v]}'");
			}
			if ($ret) {
				$this->error_msg = static::$translate[$v] . '重复！';
				return false;
			}
		}

		foreach ($this->attrs as $k => $v) {
			if ($v === NULL)
			continue;

			if (isset(static::$reg_validate[$k])) {
				$ret = preg_match(static::$reg_validate[$k]['reg'], $v);
				if (empty($ret)) {
					$this->error_msg = static::$reg_validate[$k]['notice'];
					return false;
				}
			}
		}

		return true;
	}

	public function error_msg()
	{
		return $this->error_msg;
	}




	public static function snormalize($objects)
	{
		if (is_array($objects)) {
			foreach ($objects as $k => $v) {
				$objects[$k]->normalize();
			}
		} elseif (is_object($objects)) {
			$objects->normalize();
		}
		return $objects;
	}


	public function normalize(){
		if(!$this->is_normalized){
			if(isset($this->attrs['create_time'])){
				$this->attrs['create_time'] = date('Y-m-d h:i', $this->attrs['create_time']);
			}
		}
		$this->is_normalized=true;
	}


	public static function create($attrs = array())
	{
		$object = new static::$class_name;
		$object->attrs = $attrs;
		$object->post_create();
		return $object;
	}


	public static function load($id)
	{

		$object = new static::$class_name;
		$ret = get_element_by_key(static::$table_name, static::$id_name, $id, '*');
		if (empty($ret)) {
			return false;
		}

		$object->attrs = $ret;
		$object->post_load();

		return $object;
	}


	public function get_id()
	{
		if (isset($this->attrs[static::$id_name]))
		return $this->attrs[static::$id_name];
		return false;
	}


	public static function get_object($arr)
	{

		$ret = get_element(static::$table_name,'*', $arr);
		if(empty($ret))
		return false;



		$object = new static::$class_name;
		$object->attrs=$ret[0];
		return $object;
	}





	public static function get_objects($where = 0, $order_by = 0, $limit = 0, $start = 0)
	{
		$ret = get_element(static::$table_name, '*', $where, $order_by, $limit, $start);
		$objects = array();
		foreach ($ret as $v) {
			$object = new static::$class_name;
			$object->attrs = $v;
			$objects[] = $object;
		}
		return $objects;
	}

	public static function get_count($where = 0, $order_by = 0, $limit = 0, $start = 0){
		return get_element_count(static::$table_name,$where,$order_by,$limit,$start);
	}

	/**
	 *
	 * @return insert id
	 */
	public final function insert()
	{

		$this->pre_insert();
			
		$ret = $this->validate();
		if ($ret === false) {
			return false;
		}

		$this->attrs['create_time']=time();
		if(isset($_SESSION['user_id'])){
			$this->attrs['creator_id']=$_SESSION['user_id'];
		}

		$ret_id = inserttable(static::$table_name, $this->attrs);
		if (empty($ret_id)) {
			$this->error_msg = '数据库出错！';
			return false;
		}

		$id_name=static::$id_name;
		$this->attrs[$id_name]=$ret_id;
		$this->refresh();
		$this->post_insert();
		return $ret_id;
	}

	public function refresh()
	{
		if (!$this->has_id())
		return false;
			
		$id = $this->get_id();
		$ret = get_element_by_key(static::$table_name, static::$id_name, $id, '*');
		$this->attrs = $ret;
	}

	/**
	 *
	 * @param array $attrs
	 * @return false if invalid,true successful
	 */
	public final  function update($attrs = array())
	{
		if (!$this->has_id())
		return false;

		$this->pre_update();


		if (!empty($attrs)) {
			$ret = $this->merge_attrs($attrs);
			if ($ret === false)
			return false;
		}

		$ret = $this->validate();
		if ($ret === false)
		return false;


		$ret = updatetable(static::$table_name, $this->attrs, array(static::$id_name=>$this->attrs[static::$id_name]));
		if ($ret === false) {
			$this->error_msg = '数据库出错';
			return false;
		}

		$this->refresh();

		$this->post_update();


	}

	/**
	 *
	 * @return false if invalid,true successful
	 */
	public final function delete()
	{
		if (!$this->has_id())
		return false;


		$this->pre_delete();

		if(empty($_SESSION['user_id']))
		$user_id=0;
		else
		$user_id=$_SESSION['user_id'];

		if(isset($this->attrs['is_delete'])){
			$which_role_delete = is_admin() ? 'admin' : 'user';
			updatetable(static::$table_name, array('is_delete'=>'yes','who_delete'=>$user_id,'delete_time'=>time(),'which_role_delete'=>$which_role_delete),array(static::$id_name=>$this->get_id()));
		}else
		deletetable(static::$table_name,array(static::$id_name=>$this->get_id()));
	}


	public function restore(){
		if (!$this->has_id())
		return false;

		if(!isset($this->attrs['is_delete']))
		return false;

		$this->update(array('is_delete'=>'no','who_delete'=>null,'delete_time'=>null,'which_role_delete'=>null));

	}


	public function dump()
	{
		write_log($this->attrs);
	}

	protected function post_load()
	{
	}

	protected function pre_insert()
	{
	}

	protected function post_insert()
	{
	}

	protected function pre_update()
	{
	}

	protected function post_update()
	{
	}

	protected function post_create(){

	}

	protected function pre_delete(){}

	protected function post_restore(){}

	protected function pre_restore(){}


}

?>