<?php
/**
 * Float Field domain object
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package ezp
 * @subpackage content
 */

/**
 * Float Field value object class
 */
namespace ezp\Content\Field;
class Float extends \ezp\Content\AbstractFieldType implements \ezp\Content\Interfaces\ContentFieldType
{
    /**
     * Field type identifier
     * @var string
     */
    const FIELD_IDENTIFIER = 'ezfloat';

    /**
     * @public
     * @var float
     */
    public $value = 0.0;

    /**
     * @var array Readable of properties on this object
     */
    protected $readableProperties = array(
        'value' => 'data_float',
    );

    /**
     * @var \ezp\Content\Interfaces\ContentFieldDefinition
     */
    protected $contentTypeFieldType;

    /**
     * @see \ezp\Content\Interfaces\ContentFieldType
     */
    public function __construct( \ezp\Content\Interfaces\ContentFieldDefinition $contentTypeFieldType )
    {
        if ( isset( $contentTypeFieldType->default ) )
            $this->value = $contentTypeFieldType->default;

        $this->contentTypeFieldType = $contentTypeFieldType;
        $this->types[] = self::FIELD_IDENTIFIER;
        parent::__construct( $contentTypeFieldType );
    }
}