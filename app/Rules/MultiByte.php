<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MultiByte implements Rule
{

    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($message = null)
    {
        $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mb_len = mb_strlen($value, 'utf-8');
        $len = strlen($value);
        if ($mb_len === $len){
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
        return is_null($this->message) ? "全角文字は使用できません。" : $this->message;
    }
}
