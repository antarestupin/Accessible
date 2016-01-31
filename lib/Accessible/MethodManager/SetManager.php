<?php

namespace Accessible\MethodManager;

class SetManager extends CollectionManager
{
    public static function add(&$list, $args)
    {
        self::assertArgsNumber(1, $args);

        $value = $args[0];

        foreach ($list as $item) {
            if ($item === $value) {
                return;
            }
        }

        $list[] = $value;
    }

    public static function remove(&$list, $args)
    {
        self::assertArgsNumber(1, $args);

        foreach ($list as $key => $value) {
            if ($value === $args[0]) {
                unset($list[$key]);
                return;
            }
        }
    }
}
