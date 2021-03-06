<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppModel extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $_title = [];
    protected $_forms = [];
    protected $_validates = [];
    protected $_validates_front = [];
    protected $_tables = [];

    public function getTitle()
    {
        return $this->_title;
    }

    public function getFormList()
    {
        return $this->_forms;
    }

    public function getValidateList()
    {
        return $this->_validates;
    }

    public function getTableList()
    {
        return $this->_tables;
    }
}
