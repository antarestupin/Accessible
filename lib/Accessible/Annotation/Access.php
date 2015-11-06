<?php

namespace Accessible\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Access
{
    /**
     * The property can be accessed through a getX method.
     *
     * @var string
     */
    const GET = 'get';

    /**
     * The property can be accessed through a isX method.
     *
     * @var string
     */
    const IS = 'is';

    /**
     * The property can be accessed through a hasX method.
     *
     * @var string
     */
    const HAS = 'has';

    /**
     * The property can be modified through a setX method.
     *
     * @var string
     */
    const SET = 'set';

    /**
     * The list of access rights given to the property attached to this annotation
     *
     * @var array
     */
    private $accessProperties;

    /**
     * Initializes the annotation.
     *
     * @param array $accessProperties List of access rights given to the property.
     *                                The access should be in [GET,IS,HAS,SET].
     */
    public function __construct(array $accessProperties)
    {
        $this->accessProperties = $accessProperties['value'];
    }

    /**
     * Get the list of access rights on the property.
     *
     * @return array The list of access rights.
     */
    public function getAccessProperties()
    {
        return $this->accessProperties;
    }
}
