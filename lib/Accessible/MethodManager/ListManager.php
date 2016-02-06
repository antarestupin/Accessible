<?php

namespace Accessible\MethodManager;

class ListManager extends CollectionManager
{
    /**
     * Adds an element to the list.
     *
     * @param array|Traversable $list
     * @param array $args
     */
    public static function add(&$list, $args)
    {
        self::assertArgsNumber(1, $args);

        $list[] = $args[0];
    }

    /**
     * Removes an element from the list.
     *
     * @param  array|Traversable $list
     * @param  array $args
     */
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
