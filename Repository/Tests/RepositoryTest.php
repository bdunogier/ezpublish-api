<?php
/**
 * File containing the RepositoryTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests;

use \eZ\Publish\API\Repository\Tests\BaseTest;

/**
 * Test case for operations in the Repository using in memory storage.
 *
 * @see eZ\Publish\API\Repository\Repository
 */
class RepositoryTest extends BaseTest
{
    /**
     * Test for the getCurrentUser() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getCurrentUser()
     */
    public function testGetCurrentUser()
    {
        $this->markTestIncomplete( "Test for Repository::getCurrentUser() is not implemented." );
    }

    /**
     * Test for the setCurrentUser() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::setCurrentUser()
     * 
     */
    public function testSetCurrentUser()
    {
        $this->markTestIncomplete( "Test for Repository::setCurrentUser() is not implemented." );
    }

    /**
     * Test for the hasAccess() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::hasAccess()
     * 
     */
    public function testHasAccess()
    {
        $this->markTestIncomplete( "Test for Repository::hasAccess() is not implemented." );
    }

    /**
     * Test for the hasAccess() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::hasAccess($module, $function, $user)
     * 
     */
    public function testHasAccessWithThirdParameter()
    {
        $this->markTestIncomplete( "Test for Repository::hasAccess() is not implemented." );
    }

    /**
     * Test for the canUser() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::canUser()
     * 
     */
    public function testCanUser()
    {
        $this->markTestIncomplete( "Test for Repository::canUser() is not implemented." );
    }

    /**
     * Test for the getContentService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getContentService()
     * 
     */
    public function testGetContentService()
    {
        $this->markTestIncomplete( "Test for Repository::getContentService() is not implemented." );
    }

    /**
     * Test for the getContentLanguageService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getContentLanguageService()
     */
    public function testGetContentLanguageService()
    {
        $repository = $this->getRepository();
        $this->assertInstanceOf( '\eZ\Publish\API\Repository\LanguageService', $repository->getContentLanguageService() );
    }

    /**
     * Test for the getContentTypeService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getContentTypeService()
     * 
     */
    public function testGetContentTypeService()
    {
        $this->markTestIncomplete( "Test for Repository::getContentTypeService() is not implemented." );
    }

    /**
     * Test for the getLocationService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getLocationService()
     * 
     */
    public function testGetLocationService()
    {
        $this->markTestIncomplete( "Test for Repository::getLocationService() is not implemented." );
    }

    /**
     * Test for the getTrashService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getTrashService()
     * 
     */
    public function testGetTrashService()
    {
        $this->markTestIncomplete( "Test for Repository::getTrashService() is not implemented." );
    }

    /**
     * Test for the getSectionService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getSectionService()
     */
    public function testGetSectionService()
    {
        $repository = $this->getRepository();
        $this->assertInstanceOf( '\eZ\Publish\API\Repository\SectionService', $repository->getSectionService() );
    }

    /**
     * Test for the getUserService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getUserService()
     * 
     */
    public function testGetUserService()
    {
        $this->markTestIncomplete( "Test for Repository::getUserService() is not implemented." );
    }

    /**
     * Test for the getRoleService() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::getRoleService()
     * 
     */
    public function testGetRoleService()
    {
        $this->markTestIncomplete( "Test for Repository::getRoleService() is not implemented." );
    }

    /**
     * Test for the beginTransaction() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::beginTransaction()
     * 
     */
    public function testBeginTransaction()
    {
        $this->markTestIncomplete( "Test for Repository::beginTransaction() is not implemented." );
    }

    /**
     * Test for the commit() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::commit()
     * 
     */
    public function testCommit()
    {
        $this->markTestIncomplete( "Test for Repository::commit() is not implemented." );
    }

    /**
     * Test for the commit() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::commit()
     * @expectedException \RuntimeException
     */
    public function testCommitThrowsRuntimeException()
    {
        $this->markTestIncomplete( "Test for Repository::commit() is not implemented." );
    }

    /**
     * Test for the rollback() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::rollback()
     * 
     */
    public function testRollback()
    {
        $this->markTestIncomplete( "Test for Repository::rollback() is not implemented." );
    }

    /**
     * Test for the rollback() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\Repository::rollback()
     * @expectedException \RuntimeException
     */
    public function testRollbackThrowsRuntimeException()
    {
        $this->markTestIncomplete( "Test for Repository::rollback() is not implemented." );
    }

}
