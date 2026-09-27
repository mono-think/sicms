<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TelNumber implements Rule
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
        // 電話番号書式判定
        return preg_match('/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return is_null($this->message) ? '電話番号は正しい書式で入力して下さい。' : $this->message;
    }
}
