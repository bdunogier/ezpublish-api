<?php
/**
 * File containing the eZ\Publish\API\Repository\Values\User\Limitation\SubtreeLimitation class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Values\User\Limitation;

use eZ\Publish\API\Repository\Values\User\Limitation;

abstract class SubtreeLimitation extends RoleLimitation
{
    /**
     * Constructs a role limitation with the subtree name
     */
    public function __construct()
    {
        parent::__construct( Limitation::SUBTREE );
    }
}
