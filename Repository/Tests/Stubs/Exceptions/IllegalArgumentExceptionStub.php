<?php
/**
 * File containing the IllegalArgumentExceptionStub class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests\Stubs\Exceptions;

use \eZ\Publish\API\Repository\Exceptions\IllegalArgumentException;

/**
 * Stubbed implementation of the {@link \eZ\Publish\API\Repository\Exceptions\IllegalArgumentException}
 * interface.
 *
 * @see \eZ\Publish\API\Repository\Exceptions\IllegalArgumentException
 */
class IllegalArgumentExceptionStub extends IllegalArgumentException
{
    /**
     * returns an additional error code which indicates why an action could not be performed
     * @return integer an error code
     */
    public function getErrorCode()
    {
        // TODO: Implement getErrorCode() method.
    }
}
