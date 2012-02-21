<?php
/**
 * File containing the RoleStub class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests\Stubs\Values\User;

use \eZ\Publish\API\Repository\Values\User\Role;
use \eZ\Publish\API\Repository\Values\User\Policy;

/**
 * Stubbed implementation of the {@link \eZ\Publish\API\Repository\Values\User\Role}
 * class.
 *
 * @see \eZ\Publish\API\Repository\Values\User\Role
 */
class RoleStub extends Role
{
    /**
     * @var \eZ\Publish\API\Repository\Values\User\Policy[]
     */
    protected $policies;

    /**
     * Instantiates a role stub instance.
     *
     * @param array $properties
     * @param \eZ\Publish\API\Repository\Values\User\Policy[] $policies
     */
    public function __construct( array $properties = array(), array $policies = array() )
    {
        parent::__construct( $properties );

        $this->policies = $policies;
    }

    /**
     *
     * This method returns the human readable name in all provided languages
     * of the role
     *
     * The structure of the return value is:
     * <code>
     * array( 'eng' => '<name_eng>', 'de' => '<name_de>' );
     * </code>
     *
     * @since 5.0
     *
     * @return string[]
     */
    public function getNames()
    {
        // TODO: Implement getNames() method.
    }

    /**
     * this method returns the name of the role in the given language
     *
     * @since 5.0
     *
     * @param string $languageCode
     *
     * @return string the name for the given language or null if none exists.
     */
    public function getName($languageCode)
    {
        // TODO: Implement getName() method.
    }

    /**
     * This method returns the human readable description of the role
     *
     * The structure of this field is:
     * <code>
     * array( 'eng' => '<description_eng>', 'de' => '<description_de>' );
     * </code>
     *
     * @since 5.0
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        // TODO: Implement getDescriptions() method.
    }

    /**
     * this method returns the name of the role in the given language
     *
     * @since 5.0
     *
     * @param string $languageCode
     *
     * @return string the description for the given language or null if none existis.
     */
    public function getDescription($languageCode)
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * returns the list of policies of this role
     * @return \eZ\Publish\API\Repository\Values\User\Policy[]
     */
    public function getPolicies()
    {
        return $this->policies;
    }
}
