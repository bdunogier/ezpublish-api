<?php
namespace eZ\Publish\API\Repository\Values\User\Limitation;

use eZ\Publish\API\Repository\Values\User\Limitation;

class SubtreeLimitation extends RoleLimitation
{
    /**
     * (non-PHPdoc)
     * @see User/eZ\Publish\API\Repository\Values\User.Limitation::getIdentifier()
     */
    public function getIdentifier()
    {
        return Limitation::SUBTREE;
    }
}
