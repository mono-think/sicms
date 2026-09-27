<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaNumCustom implements Rule
{

    protected $message;
    protected $length;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($message = null, $len)
    {
        $this->message = $message;
        $this->length = $len;
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
        if (! empty($value) && (preg_match("/^[a-zA-Z0-9]+$/", $value))) {
            $multi_len = mb_strlen($value, 'utf-8');
            $single_len = strlen($value);
            if ($multi_len == $single_len) {
                if ($multi_len <= $this->length) {
                    return true;
                }
            }
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
        return is_null($this->message) ? "番号は{$this->length}文字以下の半角英数字で入力して下さい。" : $this->message;
    }
}
