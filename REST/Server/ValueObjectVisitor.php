<?php
/**
 * File containing the ValueObjectVisitor class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST\Server;

/**
 * Basic ValueObjectVisitor
 */
abstract class ValueObjectVisitor
{
    /**
     * Generator
     *
     * @var Generator
     */
    protected $generator;

    /**
     * Construct from generator
     *
     * @param Generator $generator
     * @return void
     */
    public function __construct( Generator $generator )
    {
        $this->generator = $generator;
    }

    /**
     * Visit struct returned by controllers
     *
     * @param Visitor $visitor
     * @param mixed $data
     * @return void
     */
    abstract public function visit( Visitor $visitor, $data );
}

