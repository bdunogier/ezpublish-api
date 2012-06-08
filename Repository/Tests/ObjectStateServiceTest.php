<?php
/**
 * File containing the ObjectStateServiceTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests;

use \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct;
use \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct;
use \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateCreateStruct;
use \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateUpdateStruct;

/**
 * Test case for operations in the ObjectStateService using in memory storage.
 *
 * @see eZ\Publish\API\Repository\ObjectStateService
 */
class ObjectStateServiceTest extends \eZ\Publish\API\Repository\Tests\BaseTest
{
    /**
     * Test for the newObjectStateGroupCreateStruct() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::newObjectStateGroupCreateStruct()
     *
     */
    public function testNewObjectStateGroupCreateStruct()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $objectStateService = $repository->getObjectStateService();

        $objectStateGroupCreate = $objectStateService->newObjectStateGroupCreateStruct(
            'publishing'
        );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\ObjectState\\ObjectStateGroupCreateStruct',
            $objectStateGroupCreate
        );
        return $objectStateGroupCreate;
    }

    /**
     * testNewObjectStateGroupCreateStructValues
     *
     * @param ObjectStateGroupCreateStruct $objectStateGroupCreate
     * @return void
     * @depends testNewObjectStateGroupCreateStruct
     */
    public function testNewObjectStateGroupCreateStructValues( ObjectStateGroupCreateStruct $objectStateGroupCreate )
    {
        $this->assertPropertiesCorrect(
            array(
                'identifier'          => 'publishing',
                'defaultLanguageCode' => null,
                'names'               => null,
                'descriptions'        => null,
            ),
            $objectStateGroupCreate
        );
    }

    /**
     * Test for the newObjectStateGroupUpdateStruct() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::newObjectStateGroupUpdateStruct()
     *
     */
    public function testNewObjectStateGroupUpdateStruct()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $objectStateService = $repository->getObjectStateService();

        $objectStateGroupUpdate = $objectStateService->newObjectStateGroupUpdateStruct();
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\ObjectState\\ObjectStateGroupUpdateStruct',
            $objectStateGroupUpdate
        );
        return $objectStateGroupUpdate;
    }

    /**
     * testNewObjectStateGroupUpdateStructValues
     *
     * @param ObjectStateGroupUpdateStruct $objectStateGroupUpdate
     * @return void
     * @depends testNewObjectStateGroupUpdateStruct
     */
    public function testNewObjectStateGroupUpdateStructValues( ObjectStateGroupUpdateStruct $objectStateGroupUpdate )
    {
        $this->assertPropertiesCorrect(
            array(
                'identifier'          => null,
                'defaultLanguageCode' => null,
                'names'               => null,
                'descriptions'        => null,
            ),
            $objectStateGroupUpdate
        );
    }

    /**
     * Test for the newObjectStateCreateStruct() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::newObjectStateCreateStruct()
     *
     */
    public function testNewObjectStateCreateStruct()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $objectStateService = $repository->getObjectStateService();

        $objectStateCreate = $objectStateService->newObjectStateCreateStruct(
            'pending'
        );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\ObjectState\\ObjectStateCreateStruct',
            $objectStateCreate
        );
        return $objectStateCreate;
    }

    /**
     * testNewObjectStateCreateStructValues
     *
     * @param ObjectStateCreateStruct $objectStateCreate
     * @return void
     * @depends testNewObjectStateCreateStruct
     */
    public function testNewObjectStateCreateStructValues( ObjectStateCreateStruct $objectStateCreate )
    {
        $this->assertPropertiesCorrect(
            array(
                'identifier'          => 'pending',
                'priority'            => false,
                'defaultLanguageCode' => null,
                'names'               => null,
                'descriptions'        => null,
            ),
            $objectStateCreate
        );
    }

    /**
     * Test for the newObjectStateUpdateStruct() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::newObjectStateUpdateStruct()
     *
     */
    public function testNewObjectStateUpdateStruct()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::newObjectStateUpdateStruct() is not implemented." );
    }

    /**
     * Test for the createObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::createObjectStateGroup()
     *
     */
    public function testCreateObjectStateGroup()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::createObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the createObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::createObjectStateGroup()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testCreateObjectStateGroupThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::createObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the loadObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectStateGroup()
     *
     */
    public function testLoadObjectStateGroup()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the loadObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectStateGroup()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadObjectStateGroupThrowsNotFoundException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the loadObjectStateGroups() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectStateGroups()
     *
     */
    public function testLoadObjectStateGroups()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectStateGroups() is not implemented." );
    }

    /**
     * Test for the loadObjectStateGroups() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectStateGroups($offset)
     *
     */
    public function testLoadObjectStateGroupsWithFirstParameter()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectStateGroups() is not implemented." );
    }

    /**
     * Test for the loadObjectStateGroups() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectStateGroups($offset, $limit)
     *
     */
    public function testLoadObjectStateGroupsWithSecondParameter()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectStateGroups() is not implemented." );
    }

    /**
     * Test for the loadObjectStates() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectStates()
     *
     */
    public function testLoadObjectStates()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectStates() is not implemented." );
    }

    /**
     * Test for the updateObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::updateObjectStateGroup()
     *
     */
    public function testUpdateObjectStateGroup()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::updateObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the updateObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::updateObjectStateGroup()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testUpdateObjectStateGroupThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::updateObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the deleteObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::deleteObjectStateGroup()
     *
     */
    public function testDeleteObjectStateGroup()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::deleteObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the deleteObjectStateGroup() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::deleteObjectStateGroup()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testDeleteObjectStateGroupThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::deleteObjectStateGroup() is not implemented." );
    }

    /**
     * Test for the createObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::createObjectState()
     *
     */
    public function testCreateObjectState()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::createObjectState() is not implemented." );
    }

    /**
     * Test for the createObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::createObjectState()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testCreateObjectStateThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::createObjectState() is not implemented." );
    }

    /**
     * Test for the loadObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectState()
     *
     */
    public function testLoadObjectState()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectState() is not implemented." );
    }

    /**
     * Test for the loadObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::loadObjectState()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadObjectStateThrowsNotFoundException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::loadObjectState() is not implemented." );
    }

    /**
     * Test for the updateObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::updateObjectState()
     *
     */
    public function testUpdateObjectState()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::updateObjectState() is not implemented." );
    }

    /**
     * Test for the updateObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::updateObjectState()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testUpdateObjectStateThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::updateObjectState() is not implemented." );
    }

    /**
     * Test for the setPriorityOfObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::setPriorityOfObjectState()
     *
     */
    public function testSetPriorityOfObjectState()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::setPriorityOfObjectState() is not implemented." );
    }

    /**
     * Test for the setPriorityOfObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::setPriorityOfObjectState()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testSetPriorityOfObjectStateThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::setPriorityOfObjectState() is not implemented." );
    }

    /**
     * Test for the deleteObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::deleteObjectState()
     *
     */
    public function testDeleteObjectState()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::deleteObjectState() is not implemented." );
    }

    /**
     * Test for the deleteObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::deleteObjectState()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testDeleteObjectStateThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::deleteObjectState() is not implemented." );
    }

    /**
     * Test for the setObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::setObjectState()
     *
     */
    public function testSetObjectState()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::setObjectState() is not implemented." );
    }

    /**
     * Test for the setObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::setObjectState()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentExceptioon
     */
    public function testSetObjectStateThrowsInvalidArgumentExceptioon()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::setObjectState() is not implemented." );
    }

    /**
     * Test for the setObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::setObjectState()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function testSetObjectStateThrowsUnauthorizedException()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::setObjectState() is not implemented." );
    }

    /**
     * Test for the getObjectState() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::getObjectState()
     *
     */
    public function testGetObjectState()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::getObjectState() is not implemented." );
    }

    /**
     * Test for the getContentCount() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ObjectStateService::getContentCount()
     *
     */
    public function testGetContentCount()
    {
        $this->markTestIncomplete( "Test for ObjectStateService::getContentCount() is not implemented." );
    }

}
