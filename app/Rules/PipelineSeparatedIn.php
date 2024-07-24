<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PipelineSeparatedIn implements ValidationRule
{
    protected $validOptions;

    public function __construct(array $validOptions)
    {
        $this->validOptions = $validOptions;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Split the input by | and trim spaces
        $items = array_map('trim', explode('|', $value));

        // Validate each item against the valid options
        foreach ($items as $item) {
            if (!in_array($item, $this->validOptions, true)) {
                $fail(__('The :attribute contains invalid values.'));
                return;
            }
        }
    }

    public function message()
    {
        return 'One or more of the :attribute values are not valid.';
    }
}
