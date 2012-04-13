<?php
/**
 * File containing the a test class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST\Common\Tests\UrlHandler;

use eZ\Publish\API\REST\Common;

/**
 * Test case for operations in the ContentTypeService using in memory storage.
 *
 * @see eZ\Publish\API\Repository\ContentTypeService
 * @group integration
 */
class PatternTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage No URL for type 'unknown' available.
     */
    public function testParseUnknownUrlType()
    {
        $urlHandler = new Common\UrlHandler\Pattern();
        $urlHandler->parse( 'unknown', '/foo' );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Invalid pattern part: '{broken'.
     */
    public function testParseInvalidPattern()
    {
        $urlHandler = new Common\UrlHandler\Pattern( array(
            'invalid' => '/foo/{broken',
        ) );
        $urlHandler->parse( 'invalid', '/foo' );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage URL '/bar' did not match (^/foo/(?P<foo>[^/]+))S.
     */
    public function testPatternDoesNotMatch()
    {
        $urlHandler = new Common\UrlHandler\Pattern( array(
            'pattern' => '/foo/{foo}',
        ) );
        $urlHandler->parse( 'pattern', '/bar' );
    }

    public static function getParseValues()
    {
        return array(
            array(
                'section',
                '/content/section/42',
                array(
                    'section' => '42',
                )
            ),
            array(
                'objectversion',
                '/content/object/42/23',
                array(
                    'object'  => '42',
                    'version' => '23',
                )
            ),
        );
    }

    /**
     * @dataProvider getParseValues
     */
    public function testParseUrl( $type, $url, $expectedValues )
    {
        $urlHandler = $this->getWorkingUrlHandler();

        $this->assertSame(
            $expectedValues,
            $urlHandler->parse( $type, $url )
        );
    }

    protected function getWorkingUrlHandler()
    {
        return new Common\UrlHandler\Pattern( array(
            'section'       => '/content/section/{section}',
            'objectversion' => '/content/object/{object}/{version}',
        ) );
    }
}

