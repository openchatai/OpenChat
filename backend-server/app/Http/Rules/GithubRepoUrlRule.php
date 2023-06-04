<?php

namespace App\Http\Rules;


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GithubRepoUrlRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is a valid GitHub repo URL
        $pattern = '/^https?:\/\/github\.com\/[\w-]+\/[\w.-]+$/i';
        if (!preg_match($pattern, $value)){
            $fail($attribute . ' is not a valid GitHub repository URL.');
        }
    }
}
