<?php
/*=============================================================================
#     FileName: generate_model.php
#         Desc: This file is used to generate model depend on tables in database
#       Author: lubo
#        Email: lubobill1990@163.com
#     HomePage: http://www.2beeman.com
#      Version: 0.0.1
#   LastChange: 2011-10-07 04:09:29
#      History:
=============================================================================*/
include '../common.php';
define ( 'MODELPATH', S_ROOT . "source/model" . DIRECTORY_SEPARATOR );
$res = $_SGLOBAL ['db']->fetch_all ( "SELECT * FROM information_schema.`TABLES` WHERE table_schema='simuren'" );
$tables = array ();
foreach ( $res as $table ) {
	$sql = "SELECT * FROM information_schema.`COLUMNS` WHERE TABLE_NAME='$table[TABLE_NAME]'";
	$columns = $_SGLOBAL ['db']->fetch_all ( $sql );
	
	$tables [] = array ('name' => $table ['TABLE_NAME'], 'comment' => $table ['TABLE_COMMENT'], 'columns' => $columns );

}

foreach ( $tables as $table ) {
	$class_name = upperCaseEveryWord ( $table [name].'Model' );
	$file_name = strtolower ( $table [name] ).'.class';
	$fp = fopen ( MODELPATH . $file_name . ".php", 'w+' );
	
	$content = 
	"<?php\n".
	 "/**\n".
	  " * This file is generate from table:\n".
	  " * TABLE_NAME: $table[name]\n".
	  " * TABLE_COMMENT: $table[comment]\n".
	  " */\n".
	  "class $class_name extends " . "Model" . "{\n";

	$attriContent="";
	$constructionContent="";
	$getSetContent="";
	$validateContent="";
	$createInstanceContent="";
	$saveContent="";
	$deleteContent="";
	
	$attriContent=
	"\tpublic static \$tableName='".$table[name]."';\n";
	
	
	$constructionContent=
	"\n\tpublic function __construct(){\n".
	"\t\tparent::__construct();\n";

	$idNameConstructor="";
	$attriListConstructor="";
	$attriChangeRecordConstructor="";
	foreach ($table['columns'] as $key=>$value) {
		$ret=array_search("PRI", $value);
		if ($ret!=null) {
			$idNameConstructor="\t\t\$this->idName='".$value['COLUMN_NAME']."';\n";
			break;
		}
	}
	
	foreach ( $table ['columns'] as $column ) {
		
		$attriChangeRecordConstructor.=
		"\t\t\$this->attriChangeRecord['".genAttriName($column[COLUMN_NAME])."']=false;\n";
		$attriListConstructor.=
		"\t\t\$this->attriList['".genAttriName($column[COLUMN_NAME])."']=null;\n";

	}
	
	$validateContent.=
	"\tpublic function validate(){\n".
	"\t\t//这里需要填入验证对象中数据的代码\n".
	"\t}\n";
	
	$createInstanceContent.=
	"\tpublic static function createInstance(){\n".
	"\t\treturn new $class_name();\n".
	"\t}\n";
	
	$constructionContent.="\n";
	$constructionContent.=$idNameConstructor;
	$constructionContent.="\n";
	$constructionContent.=$attriListConstructor;
	$constructionContent.="\n";
	$constructionContent.=$attriChangeRecordConstructor;
	$constructionContent.="\t}\n";
	
	$content.="\n";
	$content.=$attriContent;
	$content.="\n";
	$content.=$constructionContent;
	$content.="\n";
	$content.=$getSetContent;
	$content.="\n";
	$content.=$validateContent;
	$content.="\n";
	$content.=$createInstanceContent;
	$content.="\n";
	$content .= 
		"}\n".
		"?>";
	fwrite ( $fp, $content );
	fclose ( $fp );
}


function genAttriName($str) {
	return lcfirst ( upperCaseEveryWord ( $str ) );
}

function lcfirst($str) { 
	$str[0] = strtolower($str[0]); 
	return $str; 
} 

function handleNull($str) {
	if (is_null($str)){
		return "null";
	}else{
		return "'$str'";
	}
}
?>
