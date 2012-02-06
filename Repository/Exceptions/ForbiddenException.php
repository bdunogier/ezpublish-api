<?php
namespace eZ\Publish\API\Repository\Exceptions;

use RuntimeException;

/**
 * An Exception which is thrown if an operation cannot be performed by a service
 * although the current user would have permission to perform this action.
 *
 * @package eZ\Publish\API\Repository\Exceptions
 */
abstract class ForbiddenException extends RuntimeException
{
    /**
     * Returns an additional error code which indicates why an action could not be performed
     *
     * @return int An error code
     */
    abstract function getErrorCode();
}
