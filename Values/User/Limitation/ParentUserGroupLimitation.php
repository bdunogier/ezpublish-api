<?php
namespace ezp\PublicAPI\Values\User\Limitation;

use ezp\PublicAPI\Values\User\Limitation;

class ParentUserGroupLimitation extends Limitation
{
    /**
     * (non-PHPdoc)
     * @see User/ezp\PublicAPI\Values\User.Limitation::getIdentifier()
     */
    public function getIdentifier()
    {
        return Limitation::PARENTUSERGROUP;
    }
}
