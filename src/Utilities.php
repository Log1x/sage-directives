<?php

namespace App;

class Util
{
    /**
     * Removes the paranthesis passed through the Blade directive when multiple arguments are present.
     *
     * @param  mixed $args
     * @return string
     */
    public static function Args($args)
    {
        return explode(', ', strtr($args, ['(' => '', ')' => '']));
    }
}
