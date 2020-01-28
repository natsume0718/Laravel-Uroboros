<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Twitter\Text\Parser;

class TwitterTextParser implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $parser = resolve(Parser::class);
        $parse_result = $parser->parseTweet($value);
        return $parse_result->valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attributeはTwitterに投稿できません。';
    }
}
