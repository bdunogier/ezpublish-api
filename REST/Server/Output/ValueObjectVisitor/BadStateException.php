<?php
/**
 * File containing the BadStateException visitor class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST\Server\Output\ValueObjectVisitor;

/**
 * BadStateException value object visitor
 */
class BadStateException extends Exception
{
    /**
     * Return HTTP status code
     *
     * @return int
     */
    protected function getStatus()
    {
        return 409;
    }
}

