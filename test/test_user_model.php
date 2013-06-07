<?php
require_once '../common.php';
//$user=array('name'=>'陈强','alias_name'=>'强仔','name_public'=>'0','id_card_number'=>'320404198910072214','student_number'=>'081251123','password'=>'123');
//User
//$user_object=Model::create('User',$user);
//$ret_id=$user_object->register();
////$user_object->delete();
//$user_object=Model::load('User',$ret_id);
//$array=array('avatar'=>'123.jpg','medium_avatar'=>'234.jpg','little_avatar'=>'456.jpg');
//$user_object->merge_attrs($array);
//$user_object->update();
//
//$user_object=Model::create('User');
//$ret=$user_object->login('081251123','123');
//echo $ret;
//$user_object->change_password('123','321');
//$ret=$user_object->login('081251123','321');
//echo $ret;

//delete user first
$user=User::get_objects(array('name'=>'陈强'));
sexecute_func($user,'delete');

//create model
$user=array('name'=>'陈强','alias_name'=>'强仔','name_public'=>'0','id_card_number'=>'320404198910072214','student_number'=>'081251123','password'=>'123');
$user=User::create($user);
$ret=$user->insert();
echo $ret!==false;

//unchanged undirect-access
$user=User::load($ret);
echo $user!==false;
$ret=$user->set('name','假冒的');
echo $ret===false;

//unchanged direct-access
$user->name='假冒的';
echo $user->name=='陈强';

UserPrivacy::init();
//reg validate
$user->email='123';
$user->update();
echo $user->email=='123';
$user->refresh();
echo $user->email!='123';

?>