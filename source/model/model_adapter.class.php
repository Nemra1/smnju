<?php
class ModelAdapter extends Model
{
    protected function PostLoad()
    {
    }

    protected function PreInsert()
    {
    }

    protected function PostInsert()
    {
    }

    protected function PreUpdate()
    {
    }

    protected function PostUpdate()
    {
    }

    public static $s_UserId;

    public static function load($id)
    {
        $object = parent::load($id);
        if (isset($object->is_delete) && $object->is_delete == 'yes') {
            return false;
        }
        return $object;
    }

    public function insert()
    {
        $this->PreInsert();
        $ret = parent::insert();
        $this->PostInsert();
        return $ret;
    }

    public function update($arr)
    {
        $this->PreUpdate();
        $res = parent::update($arr);
        $this->PostUpdate();
        return $res;
    }
}

ModelAdapter::$s_UserId = $_SESSION['user_id'];
?>