<?php
namespace eZ\Publish\API\Repository\Values\Content;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * This class represents a section
 */
class SectionCreateStruct extends ValueObject
{

    /**
     * Unique identifier of the section
     * 
     * @required
     *
     * @var string
     */
    public $identifier;

    /**
     * Name of the section
     *
     * @required
     *
     * @var string
     */
    public $name;
}
