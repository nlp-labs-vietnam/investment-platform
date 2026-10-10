<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class AlphaDashWithoutSlashes implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = preg_match('/^[a-z0-9_-]+$/i', $value) && !preg_match('/^-|-$/i', $value);

        if($result == false && $value != '/'){
            $fail( "Invalid :attribute. Slugs must consist of lowercase letters, numbers, hyphens, and underscores");
        }

    }

}
