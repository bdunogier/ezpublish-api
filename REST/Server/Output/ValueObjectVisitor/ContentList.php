<?php
/**
 * File containing the ContentList visitor class
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
 * ContentList value object visitor
 */
class ContentList extends ValueObjectVisitor
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
        $generator->startElement( 'ContentList' );
        $visitor->setHeader( 'Content-Type', $generator->getMediaType( 'ContentList' ) );

        $generator->startAttribute( 'href', '/content/objects' );
        $generator->endAttribute( 'href' );

        $generator->startList( 'content' );
        foreach ( $data->contents as $content )
        {
            $visitor->visitValueObject( $content );
        }
        $generator->endList( 'content' );

        $generator->endElement( 'ContentList' );
    }
}

