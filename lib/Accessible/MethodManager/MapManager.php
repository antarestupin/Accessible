<?php

namespace Accessible\MethodManager;

class MapManager extends CollectionManager
{
    /**
     * Adds an element to the map.
     *
     * @param array|Traversable $map
     * @param array $args
     */
    public static function add(&$map, $args)
    {
        self::assertArgsNumber(2, $args);

        $map[$args[0]] = $args[1];
    }

    /**
     * Removes an element from the map.
     *
     * @param  array|Traversable $map
     * @param  array $args
     */
    public static function remove(&$map, $args)
    {
        self::assertArgsNumber(1, $args);

        $key = $args[0];
        if (isset($map[$key])) {
            unset($map[$key]);
        }
    }
}
