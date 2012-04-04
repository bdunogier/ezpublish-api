<?php
/**
 * File containing the Parser class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST\Server\Input\Parser;
use eZ\Publish\API\REST\Common\Input\Parser;
use eZ\Publish\API\REST\Common\Input\ParsingDispatcher;

use eZ\Publish\API\Repository\Values\Content\SectionCreateStruct;

/**
 * Base class for input parser
 */
class SectionInput extends Parser
{
    /**
     * Parse input structure
     *
     * @param array $data
     * @return SectionCreateStruct
     */
    public function parse( array $data, ParsingDispatcher $parsingDispatcher )
    {
        return new SectionCreateStruct( array(
            'name'       => $data["name"],
            'identifier' => $data["identifier"],
        ) );
    }
}

