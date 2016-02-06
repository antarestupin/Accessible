<?php

namespace Accessible\MethodManager;

class CollectionManager extends MethodCallManager
{
    /**
     * Indicates wether given collection contains the wanted item or not.
     *
     * @param  mixed $needle
     * @param  array $haystack
     *
     * @return bool
     */
    public static function collectionContains($needle, $haystack)
    {
        foreach ($haystack as $value) {
            if ($value === $needle) {
                return true;
            }
        }

        return false;
    }
}
