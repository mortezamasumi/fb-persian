<?php

namespace Mortezamasumi\FbPersian\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Mortezamasumi\Persian\Facades\Persian;
use Closure;

class IranNid implements ValidationRule
{
    public function __construct(
        protected bool $condition = true,
        protected bool $passportNumber = false,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->condition || $this->passportNumber) {
            return;
        }

        $value = Persian::arfaTOen($value);

        if (
            preg_match('/^\d{10}$/', $value) != 1 ||
            $value === '0000000000' ||
            $value === '1111111111' ||
            $value === '2222222222' ||
            $value === '3333333333' ||
            $value === '4444444444' ||
            $value === '5555555555' ||
            $value === '6666666666' ||
            $value === '7777777777' ||
            $value === '8888888888' ||
            $value === '9999999999'
        ) {
            $fail('persian::persian.invalid_nid')->translate();
        }

        $s = 0;

        for ($i = 0; $i < 9; $i++) {
            $s += (10 - $i) * (int) substr($value, $i, 1);
        }

        $s %= 11;

        if (
            ! (($s < 2 && $s === (int) substr($value, 9, 1)) ||
                ($s >= 2 && $s === (11 - (int) substr($value, 9, 1))))
        ) {
            $fail('persian::persian.invalid_nid')->translate();
        }
    }
}
