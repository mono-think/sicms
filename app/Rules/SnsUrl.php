<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SnsUrl implements Rule
{

    protected $snsno;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($snsno)
    {
        $this->snsno = $snsno;
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
        foreach ($value as $idx => $url) {
            if (empty($url)) {
                // URL入力無し
                if (strlen($this->snsno[$idx]) > 0) {
                    // SNS選択有り
                    return false;
                }
            } else {
                // URL入力有り
                if (strlen($this->snsno[$idx]) == 0) {
                    // SNS選択無し
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "SNS種別とURL双方が設定されていない箇所があります。";
    }
}
