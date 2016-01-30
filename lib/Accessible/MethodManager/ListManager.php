<?php

namespace Accessible\MethodManager;

class ListManager extends MethodCallManager
{
    public static function add(&$list, $args)
    {
        self::assertArgsNumber(1, $args);

        $list[] = $args[0];
    }

    public static function remove(&$list, $args)
    {
        self::assertArgsNumber(1, $args);

        foreach ($list as $key => $value) {
            if ($value === $args[0]) {
                unset($list[$key]);
            }
        }
    }
}
