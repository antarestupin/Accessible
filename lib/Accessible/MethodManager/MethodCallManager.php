<?php

namespace Accessible\MethodManager;

class MethodCallManager
{
    public static function assertArgsNumber($numberOfArgs, $args)
    {
        if (sizeof($args) !== $numberOfArgs) {
            throw new \BadMethodCallException("A wrong number of arguments has been given to the called method.");
        }
    }
}
