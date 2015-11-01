<?php

namespace Accessible;

use Doctrine\Common\Annotations\Reader;

class AccessReaderFactory
{
    /**
     * The annotation reader that will be used.
     *
     * @var Reader
     */
    private static $reader = null;

    /**
     * Set the annotation reader that will be used.
     *
     * @param Reader $reader The annotation reader.
     */
    public static function setAnnotationReader(Reader $reader)
    {
        self::$reader = $reader;
    }

    /**
     * Get an instance of AccessReader.
     *
     * @return AccessReader The reader.
     */
    public static function getInstance()
    {
        return new AccessReader(self::$reader);
    }
}
