<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TagOmitStrMax implements Rule
{
    protected $max_length;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max_length)
    {
        //テキストのみでの最大文字列長を設定
        $this->max_length = $max_length;
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
        //HTMLタグ除去
        $str = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/', '', $value);
        if (mb_strlen($str, 'utf-8') <= $this->max_length){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '詳細はテキストのみ（HTMLタグ除く）で'.$this->max_length.'文字以下で入力して下さい。';
    }
}
