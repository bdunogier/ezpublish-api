<?php
/**
 * File containing the RoleList visitor class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST\Server\Output\ValueObjectVisitor;

use eZ\Publish\API\REST\Common\Output\ValueObjectVisitor;
use eZ\Publish\API\REST\Common\Output\Generator;
use eZ\Publish\API\REST\Common\Output\Visitor;

/**
 * RoleList value object visitor
 */
class RoleList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers
     *
     * @param Visitor $visitor
     * @param Generator $generator
     * @param mixed $data
     * @return void
     */
    public function visit( Visitor $visitor, Generator $generator, $data )
    {
        $generator->startElement( 'RoleList' );
        $visitor->setHeader( 'Content-Type', $generator->getMediaType( 'RoleList' ) );

        $generator->startAttribute(
            'href',
            $this->urlHandler->generate( 'roles' )
        );
        $generator->endAttribute( 'href' );

        $generator->startList( 'Role' );
        foreach ( $data->roles as $role )
        {
            $visitor->visitValueObject( $role );
        }
        $generator->endList( 'Role' );

        $generator->endElement( 'RoleList' );
    }
}

