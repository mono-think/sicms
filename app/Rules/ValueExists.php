<?php

namespace App\Rules;

use App\Models;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class ValueExists implements Rule
{
    protected $model;
    protected $colum_name;
    protected $del_flg;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Model $model, $colum_name, $del_flg = 0)
    {
        $this->model = $model;
        $this->colum_name = $colum_name;
        $this->del_flg = $del_flg;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //指定モデルに対し、値の登録有無を確認
        $ret_collection = $this->model->where($this->colum_name, $value);
        if ($this->del_flg === 0){
            $ret_collection->where('delete_flg',0);
        }
        $ret = $ret_collection->first();
        if (empty($ret)){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The {$this->colum_name} is not registered.";
    }
}
