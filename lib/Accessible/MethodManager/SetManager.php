<?php

namespace Accessible\MethodManager;

class SetManager extends CollectionManager
{
    /**
     * Adds an element to the set if it is not already present.
     *
     * @param array|Traversable $set
     * @param array $args
     */
    public static function add(&$set, $args)
    {
        self::assertArgsNumber(1, $args);

        $value = $args[0];

        foreach ($set as $item) {
            if ($item === $value) {
                return;
            }
        }

        $set[] = $value;
    }

    /**
     * Removes an element from the set.
     *
     * @param  array|Traversable $set
     * @param  array $args
     */
    public static function remove(&$set, $args)
    {
        self::assertArgsNumber(1, $args);

        foreach ($set as $key => $value) {
            if ($value === $args[0]) {
                unset($set[$key]);
                return;
            }
        }
    }
}
