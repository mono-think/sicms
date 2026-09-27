<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Captcha implements Rule
{
    protected $chk_value;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($chk_value)
    {
        $this->chk_value = $chk_value;
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
        //Captcha認証判定
        if ($this->chk_value === $value){
            //一致
            return true;
        }
        
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Captcha認証に失敗しました。再度、画像に表示の英数字を入力して下さい。';
    }
}
