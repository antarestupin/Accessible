<?php

namespace Accessible\MethodManager;

class MapManager extends MethodCallManager
{
    public static function add(&$list, $args)
    {
        self::assertArgsNumber(2, $args);

        $list[$args[0]] = $args[1];
    }

    public static function remove(&$list, $args)
    {
        self::assertArgsNumber(1, $args);

        $key = $args[0];
        if (isset($list[$key])) {
            unset($list[$key]);
        }
    }
}
