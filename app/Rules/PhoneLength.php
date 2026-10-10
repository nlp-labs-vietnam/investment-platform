<?php

namespace App\Rules;

use Closure;

use Illuminate\Contracts\Validation\ValidationRule;

class PhoneLength implements ValidationRule
{
    protected $phoneCode;
    protected $phoneLengths;


    public function __construct($phoneCode)
    {
        $this->phoneCode = $phoneCode;
        $this->phoneLengths = $this->getPhoneLengths($phoneCode);
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneLengths = $this->phoneLengths;

        if (!is_array($phoneLengths)){
            if (strlen($value) != $phoneLengths) {
                $fail("The $attribute length must be " . $phoneLengths . ' digits.');
            }
        } else {
            if (!in_array(strlen($value), $phoneLengths)) {
                $fail("The $attribute length must be one of " . implode(', ', $phoneLengths) . ' digits.');
            }
        }
    }


    private function getPhoneLengths($phoneCode)
    {
        $defaultLength = 15;

        foreach (config('country') as $country) {
            if ($country['phone_code'] == $phoneCode) {
                return $country['phoneLength'];
            }
        }

        return $defaultLength;
    }
}
