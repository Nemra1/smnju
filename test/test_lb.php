<?php
class a{
	function f1($param) {
		echo get_class($this);
	}
	function f3(){
		$class=get_class($this);
		//echo $class::$sa;
	}
}
class b extends a{
	static $sa='1';
	
	function f2(){
		echo ++self::$sa;
	}
	function f4(){
		return self::$sa;
	}
}

$ib=new b();
$ib->f2();
echo $ib->f4();
?>