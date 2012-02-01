<?php
namespace ezp\PublicAPI\Exceptions;
/**
 * This Exception is thrown if a method is called with an value referencing an object which is not in the right state
 */
abstract class BadStateException extends ForbiddenException
{
}
