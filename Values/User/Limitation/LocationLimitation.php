<?php
namespace eZ\Publish\API\Values\User\Limitation;

use eZ\Publish\API\Values\User\Limitation;

class LocationLimitation extends Limitation
{
    /**
     * (non-PHPdoc)
     * @see User/eZ\Publish\API\Values\User.Limitation::getIdentifier()
     */
    public function getIdentifier()
    {
        return Limitation::LOCATION;
    }
}
