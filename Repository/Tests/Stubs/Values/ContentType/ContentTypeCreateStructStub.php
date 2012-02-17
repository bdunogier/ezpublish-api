<?php
namespace eZ\Publish\API\Repository\Tests\Stubs\Values\ContentType;
use eZ\Publish\API\Repository\Values\ContentType\ContentTypeCreateStruct;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinitionCreateStruct;

use eZ\Publish\API\Repository\Values\Content\Location;

use eZ\Publish\API\Repository\Tests\Stubs\Exceptions;

class ContentTypeCreateStructStub extends ContentTypeCreateStruct
{
    protected $fieldDefinitions = array();

    function __construct( array $data = array() )
    {
        foreach ( $data as $propertyName => $propertyValue )
        {
            $this->$propertyName = $propertyValue;
        }
    }

    /**
     * adds a new field definition
     *
     * @param FieldDefinitionCreate $fieldDef
     */
    public function addFieldDefinition( FieldDefinitionCreateStruct $fieldDef )
    {
        $this->fieldDefinitions[] = $fieldDef;
    }
}
