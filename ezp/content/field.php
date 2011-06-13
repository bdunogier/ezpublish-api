<?php
/**
 * File containing the ezp\Content\Field class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package API
 * @subpackage Content
 */

/**
 * This class represents a Content's field
 *
 * @package API
 * @subpackage Content
 */
namespace ezp\Content;

class Field extends Base implements \ezp\DomainObjectInterface
{
    protected $fieldIdentifier;

    /**
     * Value Object (struct) for field
     * @var unknown_type
     */
    protected $value;
}
?>