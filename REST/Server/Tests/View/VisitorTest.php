<?php
/**
 * File containing a test class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST\Server\Tests\View;
use eZ\Publish\API\REST\Server\Tests\BaseTest;

use eZ\Publish\API\REST\Server\View;
use eZ\Publish\API\REST\Server\Values;
use eZ\Publish\API\REST\Common\Output;
use eZ\Publish\API\REST\Common\Message;

use Qafoo\RMF;

class SectionListTest extends BaseTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $visitorMock;

    /**
     * testVisit
     *
     * @return void
     */
    public function testVisit()
    {
        $viewVisitor = new View\Visitor( $this->getVisitorMock() );

        $request = new RMF\Request();
        $result  = new Values\SectionList( array() );

        $this->getVisitorMock()->expects( $this->once() )
            ->method( 'visit' )
            ->with( $result )
            ->will( $this->returnValue(
                new Message( array(), 'Foo Bar' )
        ) );

        ob_start();

        $viewVisitor->display( $request, $result );

        $this->assertEquals(
            'Foo Bar',
            ob_get_clean(),
            'Output not rendered correctly.'
        );
    }

    /**
     * @return void eZ\Publish\API\REST\Common\Output\Visitor
     */
    protected function getVisitorMock()
    {
        if ( !isset( $this->visitorMock ) )
        {
            $this->visitorMock = $this->getMock(
                '\\eZ\\Publish\\API\\REST\\Common\\Output\\Visitor',
                array(),
                array(),
                '',
                false
            );
        }
        return $this->visitorMock;
    }
}
