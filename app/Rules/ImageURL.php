<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ImageURL implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $headers = get_headers($value, 1);

        if(empty($headers['Content-Type'])) {
            $fail("The {$attribute} attribute is not an image url.");
        }

        $content_type = $headers['Content-Type'];
        
        if (strpos($content_type, 'image/jpeg') !== 0 
            && strpos($content_type, 'image/png') !== 0) {

                $fail("The {$attribute} attribute is not an image jpeg or png.");
        }

    }
}
