<?php

namespace Accessible\MethodManager;

class MethodCallManager
{
    /**
     * Throws a BadMethodCallException if the number of arguments is not what we want.
     *
     * @param  int $numberOfArgs
     * @param  array $args
     */
    public static function assertArgsNumber($numberOfArgs, $args)
    {
        if (sizeof($args) !== $numberOfArgs) {
            throw new \BadMethodCallException("A wrong number of arguments has been given to the called method.");
        }
    }
}
