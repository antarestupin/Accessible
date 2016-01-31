<?php

namespace Accessible\MethodManager;

class CollectionManager extends MethodCallManager
{
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
