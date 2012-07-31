<?php
/**
 * File containing the ContentServiceTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests;

use \eZ\Publish\API\Repository\Values\Content\Field;
use \eZ\Publish\API\Repository\Values\Content\Location;
use \eZ\Publish\API\Repository\Values\Content\URLAlias;
use \eZ\Publish\API\Repository\Values\Content\Relation;
use \eZ\Publish\API\Repository\Values\Content\VersionInfo;
use \eZ\Publish\API\Repository\Values\Content\Query;
use \eZ\Publish\API\Repository\Values\Content\Query\Criterion;

use \eZ\Publish\API\Repository\Exceptions\NotFoundException;

/**
 * Test case for operations in the ContentService using in memory storage.
 *
 * @see eZ\Publish\API\Repository\ContentService
 * @group content
 */
class ContentServiceTest extends BaseContentServiceTest
{
    /**
     * Test for the newContentCreateStruct() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::newContentCreateStruct()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     * @depends eZ\Publish\API\Repository\Tests\ContentTypeServiceTest::testLoadContentTypeByIdentifier
     * @group user
     */
    public function testNewContentCreateStruct()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        // Create a content type
        $contentTypeService = $repository->getContentTypeService();

        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        $contentService = $repository->getContentService();

        $contentCreate = $contentService->newContentCreateStruct( $contentType, 'eng-US' );
        /* END: Use Case */

        $this->assertInstanceOf( '\\eZ\\Publish\\API\\Repository\\Values\\Content\\ContentCreateStruct', $contentCreate );
    }

    /**
     * Test for the createContent() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testNewContentCreateStruct
     * @group user
     */
    public function testCreateContent()
    {
        if ( $this->isVersion4() )
        {
            $this->markTestSkipped( "This test requires eZ Publish 5" );
        }

        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentTypeService = $repository->getContentTypeService();

        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        $contentService = $repository->getContentService();

        $contentCreate = $contentService->newContentCreateStruct( $contentType, 'eng-US' );
        $contentCreate->setField( 'name', 'My awesome forum' );

        $contentCreate->remoteId = 'abcdef0123456789abcdef0123456789';
        $contentCreate->alwaysAvailable = true;

        $content = $contentService->createContent( $contentCreate );
        /* END: Use Case */

        $this->assertInstanceOf( '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content', $content );

        return $content;
    }

    /**
     * Test for the createContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     */
    public function testCreateContentSetsContentType( $content )
    {
        $this->assertInstanceOf( '\eZ\Publish\API\Repository\Values\ContentType\ContentType', $content->contentType );

        return $content;
    }

    /**
     * Test for the createContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentSetsContentType
     */
    public function testCreateContentSetsExpectedContentType( $content )
    {
        $this->assertEquals( 'forum', $content->contentType->identifier );
    }

    /**
     * Test for the createContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     */
    public function testCreateContentSetsContentInfo( $content )
    {
        $this->assertInstanceOf( '\\eZ\\Publish\\API\\Repository\\Values\\Content\\ContentInfo', $content->contentInfo );

        return $content;
    }

    /**
     * Test for the createContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentSetsContentInfo
     */
    public function testCreateContentSetsExpectedContentInfo( $content )
    {
        $this->assertEquals(
            array(
                $content->id,
                true,
                1,
                'abcdef0123456789abcdef0123456789',
                'eng-US',
                $this->getRepository()->getCurrentUser()->id,
                false,
                null
            ),
            array(
                $content->contentInfo->id,
                $content->contentInfo->alwaysAvailable,
                $content->contentInfo->currentVersionNo,
                $content->contentInfo->remoteId,
                $content->contentInfo->mainLanguageCode,
                $content->contentInfo->ownerId,
                $content->contentInfo->published,
                $content->contentInfo->publishedDate,
            )
        );
    }

    /**
     * Test for the createContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     */
    public function testCreateContentSetsVersionInfo( $content )
    {
        $this->assertInstanceOf( '\\eZ\\Publish\\API\\Repository\\Values\\Content\\VersionInfo', $content->getVersionInfo() );

        return $content;
    }

    /**
     * Test for the createContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentSetsVersionInfo
     */
    public function testCreateContentSetsExpectedVersionInfo( $content )
    {
        $this->assertEquals(
            array(
                'status' => VersionInfo::STATUS_DRAFT,
                'versionNo' => 1,
                'creatorId' => $this->getRepository()->getCurrentUser()->id,
                'initialLanguageCode' => 'eng-US',
            ),
            array(
                'status' => $content->getVersionInfo()->status,
                'versionNo' => $content->getVersionInfo()->versionNo,
                'creatorId' => $content->getVersionInfo()->creatorId,
                'initialLanguageCode' => $content->getVersionInfo()->initialLanguageCode,
            )
        );
    }

    /**
     * Test for the createContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     */
    public function testCreateContentThrowsInvalidArgumentException()
    {
        if ( $this->isVersion4() )
        {
            $this->markTestSkipped( "This test requires eZ Publish 5" );
        }

        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentTypeService = $repository->getContentTypeService();
        $contentService = $repository->getContentService();

        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        $contentCreate1 = $contentService->newContentCreateStruct( $contentType, 'eng-US' );
        $contentCreate1->setField( 'name', 'An awesome Sidelfingen forum' );

        $contentCreate1->remoteId = 'abcdef0123456789abcdef0123456789';
        $contentCreate1->alwaysAvailable = true;

        $contentService->createContent( $contentCreate1 );

        $contentCreate2 = $contentService->newContentCreateStruct( $contentType, 'eng-GB' );
        $contentCreate2->setField( 'name', 'An awesome Bielefeld forum' );

        $contentCreate2->remoteId = 'abcdef0123456789abcdef0123456789';
        $contentCreate2->alwaysAvailable = false;

        // This call will fail with an "InvalidArgumentException", because the
        // remoteId is already in use.
        $contentService->createContent( $contentCreate2 );
        /* END: Use Case */
    }

    /**
     * Test for the createContent() method.
     *
     * NOTE: We have bidirectional dependencies between the ContentService and
     * the LocationService, so that we cannot use PHPUnit's test dependencies
     * here.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent($contentCreateStruct, $locationCreateStructs)
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testCreateLocation
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testLoadLocationByRemoteId
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testCreateContentWithLocationCreateParameterDoesNotCreateLocationImmediately()
    {
        $repository = $this->getRepository();

        $locationService = $repository->getLocationService();

        /* BEGIN: Use Case */
        $draft = $this->createContentDraftVersion1();

        // The location will not have been created, yet, so this throws an
        // exception
        $location = $locationService->loadLocationByRemoteId(
            '0123456789abcdef0123456789abcdef'
        );
        /* END: Use Case */
    }

    /**
     * Test for the createContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent($contentCreateStruct, $locationCreateStructs)
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentWithLocationCreateParameterDoesNotCreateLocationImmediately
     */
    public function testCreateContentThrowsInvalidArgumentExceptionWithLocationCreateParameter()
    {
        $repository = $this->getRepository();

        $parentLocationId = $this->generateId( 'location', 56 );
        /* BEGIN: Use Case */
        // $parentLocationId is a valid location ID

        $contentService = $repository->getContentService();
        $contentTypeService = $repository->getContentTypeService();
        $locationService = $repository->getLocationService();

        // Load content type
        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        // Configure new locations
        $locationCreate1 = $locationService->newLocationCreateStruct( $parentLocationId );

        $locationCreate1->priority = 23;
        $locationCreate1->hidden = true;
        $locationCreate1->remoteId = '0123456789abcdef0123456789aaaaaa';
        $locationCreate1->sortField = Location::SORT_FIELD_NODE_ID;
        $locationCreate1->sortOrder = Location::SORT_ORDER_DESC;

        $locationCreate2 = $locationService->newLocationCreateStruct( $parentLocationId );

        $locationCreate2->priority = 42;
        $locationCreate2->hidden = true;
        $locationCreate2->remoteId = '0123456789abcdef0123456789bbbbbb';
        $locationCreate2->sortField = Location::SORT_FIELD_NODE_ID;
        $locationCreate2->sortOrder = Location::SORT_ORDER_DESC;

        // Configure new content object
        $contentCreate = $contentService->newContentCreateStruct( $contentType, 'eng-US' );

        $contentCreate->setField( 'name', 'A awesome Sindelfingen forum' );
        $contentCreate->remoteId = 'abcdef0123456789abcdef0123456789';
        $contentCreate->alwaysAvailable = true;

        // Create new content object under the specified location
        $contentService->createContent(
            $contentCreate,
            array( $locationCreate1 )
        );

        // This call will fail with an "InvalidArgumentException", because the
        // Content remoteId already exists,
        $contentService->createContent(
            $contentCreate,
            array( $locationCreate2 )
        );
        /* END: Use Case */
    }


    /**
     * Test for the loadContentInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentInfo()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testLoadContentInfo()
    {
        $repository = $this->getRepository();

        $anonymousUserId = $this->generateId( 'user', 10 );
        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // Load the ContentInfo for "Anonymous User"
        $contentInfo = $contentService->loadContentInfo( $anonymousUserId );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\ContentInfo',
            $contentInfo
        );
    }

    /**
     * Test for the loadContentInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentInfo()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testLoadContentInfoThrowsNotFoundException()
    {
        $repository = $this->getRepository();

        $nonExistentContentId = $this->generateId( 'object', PHP_INT_MAX );
        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // This call will fail with a NotFoundException
        $contentService->loadContentInfo( $nonExistentContentId );
        /* END: Use Case */
    }

    /**
     * Test for the loadContentInfoByRemoteId() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentInfoByRemoteId()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testLoadContentInfoByRemoteId()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // Load the ContentInfo for "Anonymous User"
        $contentInfo = $contentService->loadContentInfoByRemoteId( 'faaeb9be3bd98ed09f606fc16d144eca' );
        /* END: Use Case */

        $this->assertInstanceOf( '\\eZ\\Publish\\API\\Repository\\Values\\Content\\ContentInfo', $contentInfo );
    }

    /**
     * Test for the loadContentInfoByRemoteId() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentInfoByRemoteId()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfoByRemoteId
     */
    public function testLoadContentInfoByRemoteIdThrowsNotFoundException()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // This call will fail with a NotFoundException
        $contentService->loadContentInfoByRemoteId( 'abcdefghijklmnopqrstuvwxyz0123456789' );
        /* END: Use Case */
    }

    /**
     * Test for the loadVersionInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersionInfo()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testLoadVersionInfo()
    {
        $repository = $this->getRepository();

        $anonymousUserId = $this->generateId( 'user', 10 );
        /* BEGIN: Use Case */
        // $anonymousUserId contains the ID of the "Anonymous User"

        $contentService = $repository->getContentService();

        // Load the ContentInfo for "Anonymous User"
        $contentInfo = $contentService->loadContentInfo( $anonymousUserId );

        // Now load the current version info of the "Anonymous User"
        $versionInfo = $contentService->loadVersionInfo( $contentInfo );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\VersionInfo',
            $versionInfo
        );
    }

    /**
     * Test for the loadVersionInfoById() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersionInfoById()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testLoadVersionInfoById()
    {
        $repository = $this->getRepository();

        $anonymousUserId = $this->generateId( 'user', 10 );
        /* BEGIN: Use Case */
        // $anonymousUserId contains the ID of the "Anonymous User"

        $contentService = $repository->getContentService();

        // Load the VersionInfo for "Anonymous User"
        $versionInfo = $contentService->loadVersionInfoById( $anonymousUserId );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\VersionInfo',
            $versionInfo
        );
    }

    /**
     * Test for the loadVersionInfoById() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersionInfoById()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadVersionInfoById
     */
    public function testLoadVersionInfoByIdThrowsNotFoundException()
    {
        $repository = $this->getRepository();

        $nonExistentContentId = $this->generateId( 'object', PHP_INT_MAX );
        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // This call will fail with a "NotFoundException"
        $contentService->loadVersionInfoById( $nonExistentContentId );
        /* END: Use Case */
    }

    /**
     * Test for the loadContentByContentInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByContentInfo()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testLoadContentByContentInfo()
    {
        $repository = $this->getRepository();

        $anonymousUserId = $this->generateId( 'user', 10 );
        /* BEGIN: Use Case */
        // $anonymousUserId contains the ID of the "Anonymous User"

        $contentService = $repository->getContentService();

        // Load the ContentInfo for "Anonymous User"
        $contentInfo = $contentService->loadContentInfo( $anonymousUserId );

        // Now load the current content version for the info instance
        $content = $contentService->loadContentByContentInfo( $contentInfo );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $content
        );
    }

    /**
     * Test for the loadContentByVersionInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByVersionInfo()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadVersionInfo
     */
    public function testLoadContentByVersionInfo()
    {
        $repository = $this->getRepository();

        $anonymousUserId = $this->generateId( 'user', 10 );
        /* BEGIN: Use Case */
        // $anonymousUserId contains the ID of the "Anonymous User"

        $contentService = $repository->getContentService();

        // Load the ContentInfo for "Anonymous User"
        $contentInfo = $contentService->loadContentInfo( $anonymousUserId );

        // Load the current VersionInfo
        $versionInfo = $contentService->loadVersionInfo( $contentInfo );

        // Now load the current content version for the info instance
        $content = $contentService->loadContentByVersionInfo( $versionInfo );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $content
        );
    }

    /**
     * Test for the loadContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testLoadContent()
    {
        $repository = $this->getRepository();

        $anonymousUserId = $this->generateId( 'user', 10 );
        /* BEGIN: Use Case */
        // $anonymousUserId contains the ID of the "Anonymous User"

        $contentService = $repository->getContentService();

        // Load the Content for "Anonymous User", any language and current version
        $content = $contentService->loadContent( $anonymousUserId );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $content
        );
    }

    /**
     * Test for the loadContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContent()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     */
    public function testLoadContentThrowsNotFoundException()
    {
        $repository = $this->getRepository();

        $nonExistentContentId = $this->generateId( 'object', PHP_INT_MAX );
        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // This call will fail with a "NotFoundException"
        $contentService->loadContent( $nonExistentContentId );
        /* END: Use Case */
    }

    /**
     * Test for the loadContentByRemoteId() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByRemoteId()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testLoadContentByRemoteId()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        // Remote id of the "Anonymous" user in a eZ Publish demo installation
        $anonymousRemoteId = 'faaeb9be3bd98ed09f606fc16d144eca';

        $contentService = $repository->getContentService();

        // Load the Content for "Anonymous User"
        $content = $contentService->loadContentByRemoteId( $anonymousRemoteId );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $content
        );
    }

    /**
     * Test for the loadContentByRemoteId() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByRemoteId()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByRemoteId
     */
    public function testLoadContentByRemoteIdThrowsNotFoundException()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // This call will fail with a "NotFoundException", because no content
        // object exists for the given remoteId
        $contentService->loadContentByRemoteId( 'a1b1c1d1e1f1a2b2c2d2e2f2a3b3c3d3' );
        /* END: Use Case */
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadVersionInfo
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentWithLocationCreateParameterDoesNotCreateLocationImmediately
     */
    public function testPublishVersion()
    {
        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $content
        );

        return $content;
    }

    /**
     * Test for the publishVersion() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testPublishVersionSetsExpectedContentInfo( $content )
    {
        $this->assertEquals(
            array(
                $content->id,
                true,
                1,
                'abcdef0123456789abcdef0123456789',
                'eng-US',
                $this->getRepository()->getCurrentUser()->id,
                true,
            ),
            array(
                $content->contentInfo->id,
                $content->contentInfo->alwaysAvailable,
                $content->contentInfo->currentVersionNo,
                $content->contentInfo->remoteId,
                $content->contentInfo->mainLanguageCode,
                $content->contentInfo->ownerId,
                $content->contentInfo->published
            )
        );

        $date = new \DateTime( '1984/01/01' );
        $this->assertGreaterThan(
            $date->getTimestamp(),
            $content->contentInfo->publishedDate->getTimestamp()
        );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testPublishVersionSetsExpectedVersionInfo( $content )
    {
        $this->assertEquals(
            array(
                $this->getRepository()->getCurrentUser()->id,
                'eng-US',
                VersionInfo::STATUS_PUBLISHED,
                1
            ),
            array(
                $content->getVersionInfo()->creatorId,
                $content->getVersionInfo()->initialLanguageCode,
                $content->getVersionInfo()->status,
                $content->getVersionInfo()->versionNo
            )
        );

        $date = new \DateTime( '1984/01/01' );
        $this->assertGreaterThan(
            $date->getTimestamp(),
            $content->getVersionInfo()->modificationDate->getTimestamp()
        );

        $this->assertNotNull( $content->getVersionInfo()->modificationDate );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentWithLocationCreateParameterDoesNotCreateLocationImmediately
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testPublishVersionCreatesLocationsDefinedOnCreate()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();
        /* END: Use Case */

        $locationService = $repository->getLocationService();
        $location = $locationService->loadLocationByRemoteId(
            '0123456789abcdef0123456789abcdef'
        );

        $this->assertEquals(
            $location->getContentInfo(),
            $content->getVersionInfo()->getContentInfo()
        );

        return array( $content, $location );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionCreatesLocationsDefinedOnCreate
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testCreateLocation
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testLoadLocationByRemoteId
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentWithLocationCreateParameterDoesNotCreateLocationImmediately
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testCreateContentWithLocationCreateParameterSetsMainLocationId( array $testData )
    {
        list( $content, $location ) = $testData;

        $this->assertEquals(
            $content->getVersionInfo()->getContentInfo()->mainLocationId,
            $location->id
        );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testPublishVersionThrowsBadStateException()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createContentDraftVersion1();

        // Publish the content draft
        $contentService->publishVersion( $draft->getVersionInfo() );

        // This call will fail with a "BadStateException", because the version
        // is already published.
        $contentService->publishVersion( $draft->getVersionInfo() );
        /* END: Use Case */
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testCreateContentDraft()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Now we create a new draft from the published content
        $draftedContent = $contentService->createContentDraft( $content->contentInfo );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $draftedContent
        );

        return $draftedContent;
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $draft
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testCreateContentDraftSetsExpectedProperties( $draft )
    {
        $this->assertEquals(
            array(
                'fieldCount' => 2,
                'relationCount' => 0
            ),
            array(
                'fieldCount' => count( $draft->getFields() ),
                'relationCount' => count( $draft->getRelations() )
            )
        );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $draft
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testCreateContentDraftSetsContentInfo( $draft )
    {
        $contentInfo = $draft->contentInfo;

        $this->assertEquals(
            array(
                $draft->id,
                true,
                1,
                'eng-US',
                $this->getRepository()->getCurrentUser()->id,
                'abcdef0123456789abcdef0123456789',
                1
            ),
            array(
                $contentInfo->id,
                $contentInfo->alwaysAvailable,
                $contentInfo->currentVersionNo,
                $contentInfo->mainLanguageCode,
                $contentInfo->ownerId,
                $contentInfo->remoteId,
                $contentInfo->sectionId
            )
        );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $draft
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testCreateContentDraftSetsVersionInfo( $draft )
    {
        $versionInfo = $draft->getVersionInfo();

        $this->assertEquals(
            array(
                'creatorId' => $this->getRepository()->getCurrentUser()->id,
                'initialLanguageCode' => 'eng-US',
                'languageCodes' => array( 0 => 'eng-US' ),
                'status' => VersionInfo::STATUS_DRAFT,
                'versionNo' => 2
            ),
            array(
                'creatorId' => $versionInfo->creatorId,
                'initialLanguageCode' => $versionInfo->initialLanguageCode,
                'languageCodes' => $versionInfo->languageCodes,
                'status' => $versionInfo->status,
                'versionNo' => $versionInfo->versionNo
            )
        );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $draft
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadVersionInfo
     */
    public function testCreateContentDraftLoadVersionInfoStillLoadsPublishedVersion( $draft )
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Now we create a new draft from the published content
        $contentService->createContentDraft( $content->contentInfo );

        // This call will still load the published version
        $versionInfoPublished = $contentService->loadVersionInfo( $content->contentInfo );
        /* END: Use Case */

        $this->assertEquals( 1, $versionInfoPublished->versionNo );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testCreateContentDraftLoadContentStillLoadsPublishedVersion()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Now we create a new draft from the published content
        $contentService->createContentDraft( $content->contentInfo );

        // This call will still load the published content version
        $contentPublished = $contentService->loadContent( $content->id );
        /* END: Use Case */

        $this->assertEquals( 1, $contentPublished->getVersionInfo()->versionNo );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByRemoteId
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testCreateContentDraftLoadContentByRemoteIdStillLoadsPublishedVersion()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Now we create a new draft from the published content
        $contentService->createContentDraft( $content->contentInfo );

        // This call will still load the published content version
        $contentPublished = $contentService->loadContentByRemoteId( 'abcdef0123456789abcdef0123456789' );
        /* END: Use Case */

        $this->assertEquals( 1, $contentPublished->getVersionInfo()->versionNo );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByContentInfo
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testCreateContentDraftLoadContentByContentInfoStillLoadsPublishedVersion()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Now we create a new draft from the published content
        $contentService->createContentDraft( $content->contentInfo );

        // This call will still load the published content version
        $contentPublished = $contentService->loadContentByContentInfo( $content->contentInfo );
        /* END: Use Case */

        $this->assertEquals( 1, $contentPublished->getVersionInfo()->versionNo );
    }

    /**
     * Test for the newContentUpdateStruct() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::newContentUpdateStruct()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testNewContentUpdateStruct()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        $updateStruct = $contentService->newContentUpdateStruct();
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\ContentUpdateStruct',
            $updateStruct
        );
    }

    /**
     * Test for the updateContent() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::updateContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testNewContentUpdateStruct
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testUpdateContent()
    {
        /* BEGIN: Use Case */
        $draftVersion2 = $this->createUpdatedDraftVersion2();
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $draftVersion2
        );

        return $draftVersion2;
    }

    /**
     * Test for the updateContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     */
    public function testUpdateContentSetsExpectedFields( $content )
    {
        $actual = $this->normalizeFields( $content->getFields() );

        $expected = array(
            new Field(
                array(
                    'id' => 0,
                    'value' => null,
                    'languageCode' => 'eng-GB',
                    'fieldDefIdentifier' => 'description'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => null,
                    'languageCode' => 'eng-US',
                    'fieldDefIdentifier' => 'description'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => true,
                    'languageCode' => 'eng-GB',
                    'fieldDefIdentifier' => 'name'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => true,
                    'languageCode' => 'eng-US',
                    'fieldDefIdentifier' => 'name'
                )
            ),
        );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * Test for the updateContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContent()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     */
    public function testUpdateContentThrowsBadStateException()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Now create an update struct and modify some fields
        $contentUpdateStruct = $contentService->newContentUpdateStruct();
        $contentUpdateStruct->setField( 'title', 'An awesome² story about ezp.' );
        $contentUpdateStruct->setField( 'title', 'An awesome²³ story about ezp.', 'eng-GB' );

        $contentUpdateStruct->initialLanguageCode = 'eng-US';

        // This call will fail with a "BadStateException", because $publishedContent
        // is not a draft.
        $contentService->updateContent(
            $content->getVersionInfo(),
            $contentUpdateStruct
        );
        /* END: Use Case */
    }

    /**
     * Test for the updateContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContent()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\ContentValidationException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     */
    public function testUpdateContentThrowsContentValidationExceptionWhenInitialLanguageCodeIsNotSet()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createContentDraftVersion1();

        // Now create an update struct and modify some fields
        $contentUpdateStruct = $contentService->newContentUpdateStruct();
        $contentUpdateStruct->setField( 'name', 'An awesome² Sindelfingen forum' );

        // Don't set this, then the above call without languageCode will fail
        //$contentUpdateStruct->initialLanguageCode = 'eng-US';

        // This call will fail with a "ContentValidationException", because
        // "title" was set without a languageCode and no initialLanguageCode
        // is set.
        $contentService->updateContent(
            $draft->getVersionInfo(),
            $contentUpdateStruct
        );
        /* END: Use Case */
    }

    /**
     * Test for the updateContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContent()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\ContentValidationException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     */
    public function testUpdateContentThrowsContentValidationExceptionWhenMandatoryFieldIsEmpty()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createContentDraftVersion1();

        // Now create an update struct and modify some fields
        $contentUpdateStruct = $contentService->newContentUpdateStruct();
        $contentUpdateStruct->setField( 'title', null );

        // Don't set this, then the above call without languageCode will fail
        $contentUpdateStruct->initialLanguageCode = 'eng-US';

        // This call will fail with a "ContentValidationException", because the
        // mandatory "title" field is empty.
        $contentService->updateContent(
            $draft->getVersionInfo(),
            $contentUpdateStruct
        );
        /* END: Use Case */
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft($contentInfo, $versionInfo)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     */
    public function testCreateContentDraftWithSecondParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();

        // Now we create a new draft from the initial version
        $draftedContentReloaded = $contentService->createContentDraft(
            $contentVersion2->contentInfo,
            $contentVersion2->getVersionInfo()
        );
        /* END: Use Case */

        $this->assertEquals( 3, $draftedContentReloaded->getVersionInfo()->versionNo );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     */
    public function testPublishVersionFromContentDraft()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();
        /* END: Use Case */

        $versionInfo = $contentService->loadVersionInfo( $contentVersion2->contentInfo );

        $this->assertEquals(
            array(
                'status' => VersionInfo::STATUS_PUBLISHED,
                'versionNo' => 2
            ),
            array(
                'status' => $versionInfo->status,
                'versionNo' => $versionInfo->versionNo
            )
        );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testPublishVersionFromContentDraftArchivesOldVersion()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();
        /* END: Use Case */

        $versionInfo = $contentService->loadVersionInfo( $contentVersion2->contentInfo, 1 );

        $this->assertEquals(
            array(
                'status' => VersionInfo::STATUS_ARCHIVED,
                'versionNo' => 1
            ),
            array(
                'status' => $versionInfo->status,
                'versionNo' => $versionInfo->versionNo
            )
        );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testPublishVersionFromContentDraftUpdatesContentInfoCurrentVersion()
    {
        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();
        /* END: Use Case */

        $this->assertEquals( 2, $contentVersion2->contentInfo->currentVersionNo );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testPublishVersionFromOldContentDraftArchivesNewerVersionNo()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Create a new draft with versionNo = 2
        $draftedContentVersion2 = $contentService->createContentDraft( $content->contentInfo );

        // Create another new draft with versionNo = 3
        $draftedContentVersion3 = $contentService->createContentDraft( $content->contentInfo );

        // Publish draft with versionNo = 3
        $contentService->publishVersion( $draftedContentVersion3->getVersionInfo() );

        // Publish the first draft with versionNo = 2
        // currentVersionNo is now 2, versionNo 3 will be archived
        $publishedDraft = $contentService->publishVersion( $draftedContentVersion2->getVersionInfo() );
        /* END: Use Case */

        $this->assertEquals( 2, $publishedDraft->contentInfo->currentVersionNo );
    }

    /**
     * Test for the newContentMetadataUpdateStruct() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::newContentMetadataUpdateStruct()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testNewContentMetadataUpdateStruct()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // Creates a new metadata update struct
        $metadataUpdate = $contentService->newContentMetadataUpdateStruct();

        $metadataUpdate->remoteId = 'aaaabbbbccccddddeeeeffff11112222';
        $metadataUpdate->mainLanguageCode = 'eng-GB';
        $metadataUpdate->alwaysAvailable = false;
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\ContentMetadataUpdateStruct',
            $metadataUpdate
        );
    }

    /**
     * Test for the updateContentMetadata() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::updateContentMetadata()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testNewContentMetadataUpdateStruct
     */
    public function testUpdateContentMetadata()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // Creates a metadata update struct
        $metadataUpdate = $contentService->newContentMetadataUpdateStruct();

        $metadataUpdate->remoteId = 'aaaabbbbccccddddeeeeffff11112222';
        $metadataUpdate->mainLanguageCode = 'eng-GB';
        $metadataUpdate->alwaysAvailable = false;
        $metadataUpdate->publishedDate = $this->getRepository()->createDateTime( 441759600 ); // 1984/01/01
        $metadataUpdate->modificationDate = $this->getRepository()->createDateTime( 441759600 ); // 1984/01/01

        // Update the metadata of the published content object
        $content = $contentService->updateContentMetadata(
            $content->contentInfo,
            $metadataUpdate
        );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $content
        );

        return $content;
    }

    /**
     * Test for the updateContentMetadata() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContentMetadata()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContentMetadata
     */
    public function testUpdateContentMetadataSetsExpectedProperties( $content )
    {
        $contentInfo = $content->contentInfo;

        $this->assertEquals(
            array(
                'remoteId' => 'aaaabbbbccccddddeeeeffff11112222',
                'sectionId' => $this->generateId( 'section', 1 ),
                'alwaysAvailable' => false,
                'currentVersionNo' => 1,
                'mainLanguageCode' => 'eng-GB',
                'modificationDate' => $this->getRepository()->createDateTime( 441759600 ),
                'ownerId' => $this->getRepository()->getCurrentUser()->id,
                'published' => true,
                'publishedDate' => $this->getRepository()->createDateTime( 441759600 ),
            ),
            array(
                'remoteId' => $contentInfo->remoteId,
                'sectionId' => $contentInfo->sectionId,
                'alwaysAvailable' => $contentInfo->alwaysAvailable,
                'currentVersionNo' => $contentInfo->currentVersionNo,
                'mainLanguageCode' => $contentInfo->mainLanguageCode,
                'modificationDate' => $contentInfo->modificationDate,
                'ownerId' => $contentInfo->ownerId,
                'published' => $contentInfo->published,
                'publishedDate' => $contentInfo->publishedDate,
            )
        );
    }

    /**
     * Test for the updateContentMetadata() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContentMetadata()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContentMetadata
     */
    public function testUpdateContentMetadataNotUpdatesContentVersion( $content )
    {
        $this->assertEquals( 1, $content->getVersionInfo()->versionNo );
    }

    /**
     * Test for the updateContentMetadata() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContentMetadata()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContentMetadata
     */
    public function testUpdateContentMetadataThrowsInvalidArgumentException()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // RemoteId of the "Media" page of an eZ Publish demo installation
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';

        $content = $this->createContentVersion1();

        // Creates a metadata update struct
        $metadataUpdate = $contentService->newContentMetadataUpdateStruct();
        $metadataUpdate->remoteId = $mediaRemoteId;

        // This call will fail with an "InvalidArgumentException", because the
        // specified remoteId is already used by the "Media" page.
        $contentService->updateContentMetadata(
            $content->contentInfo,
            $metadataUpdate
        );
        /* END: Use Case */
    }

    /**
     * Test for the deleteContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteContent()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testDeleteContent()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();
        $locationService = $repository->getLocationService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();

        // Load the locations for this content object
        $locations = $locationService->loadLocations( $contentVersion2->contentInfo );

        // This will delete the content, all versions and the associated locations
        $contentService->deleteContent( $contentVersion2->contentInfo );
        /* END: Use Case */

        foreach ( $locations as $location )
        {
            $locationService->loadLocation( $location->id );
        }
    }

    /**
     * Test for the loadContentDrafts() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentDrafts()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testLoadContentDraftsReturnsEmptyArrayByDefault()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        $contentDrafts = $contentService->loadContentDrafts();
        /* END: Use Case */

        $this->assertSame( array(), $contentDrafts );
    }

    /**
     * Test for the loadContentDrafts() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentDrafts()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     */
    public function testLoadContentDrafts()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        // Remote ids of the "Media" and the "eZ Publish Demo Design ..." page
        // of a eZ Publish demo installation.
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';
        $demoDesignRemoteId = '8b8b22fe3c6061ed500fbd2b377b885f';

        $contentService = $repository->getContentService();

        // "Media" content object
        $mediaContentInfo = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );

        // "eZ Publish Demo Design ..." content object
        $demoDesignContentInfo = $contentService->loadContentInfoByRemoteId( $demoDesignRemoteId );

        // Create some drafts
        $contentService->createContentDraft( $mediaContentInfo );
        $contentService->createContentDraft( $demoDesignContentInfo );

        // Now $contentDrafts should contain two drafted versions
        $draftedVersions = $contentService->loadContentDrafts();
        /* END: Use Case */

        $actual = array(
            $draftedVersions[0]->status,
            $draftedVersions[0]->getContentInfo()->remoteId,
            $draftedVersions[1]->status,
            $draftedVersions[1]->getContentInfo()->remoteId,
        );
        sort( $actual, SORT_STRING );

        $this->assertEquals(
            array(
                VersionInfo::STATUS_DRAFT,
                VersionInfo::STATUS_DRAFT,
                $demoDesignRemoteId,
                $mediaRemoteId,
            ),
            $actual
        );
    }

    /**
     * Test for the loadContentDrafts() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentDrafts($user)
     *
     */
    public function testLoadContentDraftsWithFirstParameter()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $user = $this->createUserVersion1();

        // Get current user
        $oldCurrentUser = $repository->getCurrentUser();

        // Set new editor as user
        $repository->setCurrentUser( $user );

        // Remote id of the "Media" content object in an eZ Publish demo installation.
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';

        $contentService = $repository->getContentService();

        // "Media" content object
        $mediaContentInfo = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );

        // Create a content draft
        $contentService->createContentDraft( $mediaContentInfo );

        // Reset to previous current user
        $repository->setCurrentUser( $oldCurrentUser );

        // Now $contentDrafts for the previous current user and the new user
        $newCurrentUserDrafts = $contentService->loadContentDrafts( $user );
        $oldCurrentUserDrafts = $contentService->loadContentDrafts( $oldCurrentUser );
        /* END: Use Case */

        $this->assertSame( array(), $oldCurrentUserDrafts );

        $this->assertEquals(
            array(
                VersionInfo::STATUS_DRAFT,
                $mediaRemoteId,
            ),
            array(
                $newCurrentUserDrafts[0]->status,
                $newCurrentUserDrafts[0]->getContentInfo()->remoteId
            )
        );
    }

    /**
     * Test for the loadVersionInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersionInfo($contentInfo, $versionNo)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testLoadVersionInfoWithSecondParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();

        // Will return the $versionInfo of $content
        $versionInfo = $contentService->loadVersionInfo( $contentVersion2->contentInfo, 1 );
        /* END: Use Case */

        $this->assertEquals( 1, $versionInfo->versionNo );
    }

    /**
     * Test for the loadVersionInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersionInfo($contentInfo, $versionNo)
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadVersionInfoWithSecondParameter
     */
    public function testLoadVersionInfoThrowsNotFoundExceptionWithSecondParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createContentDraftVersion1();

        // This call will fail with a "NotFoundException", because not versionNo
        // 2 exists for this content object.
        $contentService->loadVersionInfo( $draft->contentInfo, 2 );
        /* END: Use Case */
    }

    /**
     * Test for the loadVersionInfoById() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersionInfoById($contentId, $versionNo)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadVersionInfoWithSecondParameter
     */
    public function testLoadVersionInfoByIdWithSecondParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();

        // Will return the $versionInfo of $content
        $versionInfo = $contentService->loadVersionInfoById( $contentVersion2->id, 1 );
        /* END: Use Case */

        $this->assertEquals( 1, $versionInfo->versionNo );
    }

    /**
     * Test for the loadVersionInfoById() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersionInfoById($contentId, $versionNo)
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadVersionInfoByIdThrowsNotFoundExceptionWithSecondParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // This call will fail with a "NotFoundException", because not versionNo
        // 2 exists for this content object.
        $contentService->loadVersionInfoById( $content->id, 2 );
        /* END: Use Case */
    }

    /**
     * Test for the loadContentByVersionInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByVersionInfo($versionInfo, $languages)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByVersionInfo
     */
    public function testLoadContentByVersionInfoWithSecondParameter()
    {
        $repository = $this->getRepository();

        $sectionId = $this->generateId( 'section', 1 );
        /* BEGIN: Use Case */
        $contentTypeService = $repository->getContentTypeService();

        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        $contentService = $repository->getContentService();

        $contentCreateStruct = $contentService->newContentCreateStruct( $contentType, 'eng-US' );

        $contentCreateStruct->setField( 'name', 'Sindelfingen forum²' );

        $contentCreateStruct->setField( 'name', 'Sindelfingen forum²³', 'eng-GB' );

        $contentCreateStruct->remoteId = 'abcdef0123456789abcdef0123456789';
        // $sectionId contains the ID of section 1
        $contentCreateStruct->sectionId = $sectionId;
        $contentCreateStruct->alwaysAvailable = true;

        // Create a new content draft
        $content = $contentService->createContent( $contentCreateStruct );

        // Now publish this draft
        $publishedContent = $contentService->publishVersion( $content->getVersionInfo() );

        // Will return a content instance with fields in "eng-US"
        $reloadedContent = $contentService->loadContentByVersionInfo(
            $publishedContent->getVersionInfo(),
            array(
                'eng-GB'
            )
        );
        /* END: Use Case */

        $actual = array();
        foreach ( $reloadedContent->getFields() as $field )
        {
            $actual[] = new Field(
                array(
                    'id' => 0,
                    'value' => ( $field->value !== null ? true : null ), // Actual value tested by FieldType integration tests
                    'languageCode' => $field->languageCode,
                    'fieldDefIdentifier' => $field->fieldDefIdentifier
                )
            );
        }
        usort( $actual, function ( $field1, $field2 ) {
            if ( 0 === ( $return = strcasecmp( $field1->fieldDefIdentifier, $field2->fieldDefIdentifier ) ) )
            {
                return strcasecmp( $field1->languageCode, $field2->languageCode );
            }
            return $return;
        } );

        $expected = array(
            new Field(
                array(
                    'id' => 0,
                    'value' => null,
                    'languageCode' => 'eng-GB',
                    'fieldDefIdentifier' => 'description'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => true,
                    'languageCode' => 'eng-GB',
                    'fieldDefIdentifier' => 'name'
                )
            ),
        );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * Test for the loadContentByContentInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByContentInfo($contentInfo, $languages)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByContentInfo
     */
    public function testLoadContentByContentInfoWithSecondParameter()
    {
        $repository = $this->getRepository();

        $sectionId = $this->generateId( 'section', 1 );
        /* BEGIN: Use Case */
        $contentTypeService = $repository->getContentTypeService();

        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        $contentService = $repository->getContentService();

        $contentCreateStruct = $contentService->newContentCreateStruct( $contentType, 'eng-US' );

        $contentCreateStruct->setField( 'name', 'Sindelfingen forum²' );

        $contentCreateStruct->setField( 'name', 'Sindelfingen forum²³', 'eng-GB' );

        $contentCreateStruct->remoteId = 'abcdef0123456789abcdef0123456789';
        // $sectionId contains the ID of section 1
        $contentCreateStruct->sectionId = $sectionId;
        $contentCreateStruct->alwaysAvailable = true;

        // Create a new content draft
        $content = $contentService->createContent( $contentCreateStruct );

        // Now publish this draft
        $publishedContent = $contentService->publishVersion( $content->getVersionInfo() );

        // Will return a content instance with fields in "eng-US"
        $reloadedContent = $contentService->loadContentByContentInfo(
            $publishedContent->contentInfo,
            array(
                'eng-US'
            )
        );
        /* END: Use Case */

        $actual = $this->normalizeFields( $reloadedContent->getFields() );

        $expected = array(
            new Field(
                array(
                    'id' => 0,
                    'value' => null,
                    'languageCode' => 'eng-US',
                    'fieldDefIdentifier' => 'description'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => true,
                    'languageCode' => 'eng-US',
                    'fieldDefIdentifier' => 'name'
                )
            ),
        );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * Test for the loadContentByContentInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByContentInfo($contentInfo, $languages, $versionNo)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByContentInfo
     * @todo Fix method name to be more descriptive
     */
    public function testLoadContentByContentInfoWithThirdParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();

        // Will return a Content instance equal to $content
        $contentReloaded = $contentService->loadContentByContentInfo(
            $contentVersion2->contentInfo,
            null,
            1
        );
        /* END: Use Case */

        $this->assertEquals(
            1,
            $contentReloaded->getVersionInfo()->versionNo
        );
    }

    /**
     * Test for the loadContentByContentInfo() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByContentInfo($contentInfo, $languages, $versionNo)
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByContentInfoWithThirdParameter
     */
    public function testLoadContentByContentInfoThrowsNotFoundExceptionWithThirdParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // This call will fail with a "NotFoundException", because no content
        // with versionNo = 2 exists.
        $contentService->loadContentByContentInfo( $content->contentInfo, null, 2 );
        /* END: Use Case */
    }

    /**
     * Test for the loadContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContent($contentId, $languages)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testLoadContentWithSecondParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createMultipleLanguageDraftVersion1();

        // This draft contains those fields localized with "eng-GB"
        $draftLocalized = $contentService->loadContent( $draft->id, array( 'eng-GB' ) );
        /* END: Use Case */

        $this->assertLocaleFieldsEquals( $draftLocalized->getFields(), 'eng-GB' );
    }

    /**
     * Test for the loadContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContent($contentId, $languages, $versionNo)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testLoadContentWithThirdParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draftVersion2 = $this->createContentDraftVersion2();

        // This content instance is identical to $contentVersion1
        $oldContent = $contentService->loadContent( $draftVersion2->id, null, 1 );
        /* END: Use Case */

        $this->assertEquals( 1, $oldContent->getVersionInfo()->versionNo );
    }

    /**
     * Test for the loadContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContent($contentId, $languages, $versionNo)
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentWithThirdParameter
     */
    public function testLoadContentThrowsNotFoundExceptionWithThirdParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // This call will fail with a "NotFoundException", because for this
        // content object no versionNo=2 exists.
        $contentService->loadContent( $content->id, null, 2 );
        /* END: Use Case */
    }

    /**
     * Test for the loadContentByRemoteId() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByRemoteId($remoteId, $languages)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testLoadContentByRemoteIdWithSecondParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createMultipleLanguageDraftVersion1();

        // This draft contains those fields localized with "eng-GB"
        $draftLocalized = $contentService->loadContentByRemoteId(
            $draft->contentInfo->remoteId,
            array( 'eng-GB' )
        );
        /* END: Use Case */

        $this->assertLocaleFieldsEquals( $draftLocalized->getFields(), 'eng-GB' );
    }

    /**
     * Test for the loadContentByRemoteId() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByRemoteId($remoteId, $languages, $versionNo)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testLoadContentByRemoteIdWithThirdParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draftVersion2 = $this->createContentDraftVersion2();

        // This content instance is identical to $contentVersion1
        $oldContent = $contentService->loadContentByRemoteId(
            $draftVersion2->contentInfo->remoteId,
            null,
            1
        );
        /* END: Use Case */

        $this->assertEquals( 1, $oldContent->getVersionInfo()->versionNo );
    }

    /**
     * Test for the loadContentByRemoteId() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadContentByRemoteId($remoteId, $languages, $versionNo)
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentByRemoteIdWithThirdParameter
     */
    public function testLoadContentByRemoteIdThrowsNotFoundExceptionWithThirdParameter()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // This call will fail with a "NotFoundException", because for this
        // content object no versionNo=2 exists.
        $contentService->loadContentByRemoteId(
            $content->contentInfo->remoteId,
            null,
            2
        );
        /* END: Use Case */
    }

    /**
     * Test for the deleteVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteVersion()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     */
    public function testDeleteVersion()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createContentDraftVersion1();

        // Delete the previously created draft
        $contentService->deleteVersion( $draft->getVersionInfo() );
        /* END: Use Case */

        $contentService->loadContent( $draft->id );
    }

    /**
     * Test for the deleteVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteVersion()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testDeleteVersion
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testDeleteVersionThrowsBadStateException()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $content = $this->createContentVersion1();

        // This call will fail with a "BadStateException", because the content
        // version is currently published.
        $contentService->deleteVersion( $content->getVersionInfo() );
        /* END: Use Case */
    }

    /**
     * Test for the loadVersions() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadVersions()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testLoadVersions()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();

        // Load versions of this ContentInfo instance
        $versions = $contentService->loadVersions( $contentVersion2->contentInfo );
        /* END: Use Case */

        $this->assertEquals(
            array(
                $contentService->loadVersionInfo( $contentVersion2->contentInfo, 1 ),
                $contentService->loadVersionInfo( $contentVersion2->contentInfo, 2 )
            ),
            $versions
        );
    }

    /**
     * Test for the copyContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::copyContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testCopyContent()
    {
        $parentLocationId = $this->generateId( 'location', 56 );

        $repository = $this->getRepository();

        $contentService = $repository->getContentService();
        $locationService = $repository->getLocationService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createMultipleLanguageContentVersion2();

        // Configure new target location
        $targetLocationCreate = $locationService->newLocationCreateStruct( $parentLocationId );

        $targetLocationCreate->priority = 42;
        $targetLocationCreate->hidden = true;
        $targetLocationCreate->remoteId = '01234abcdef5678901234abcdef56789';
        $targetLocationCreate->sortField = Location::SORT_FIELD_NODE_ID;
        $targetLocationCreate->sortOrder = Location::SORT_ORDER_DESC;

        // Copy content with all versions and drafts
        $contentCopied = $contentService->copyContent(
            $contentVersion2->contentInfo,
            $targetLocationCreate
        );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $contentCopied
        );

        $this->assertNotEquals(
            $contentVersion2->contentInfo->remoteId,
            $contentCopied->contentInfo->remoteId
        );

        $this->assertNotEquals(
            $contentVersion2->id,
            $contentCopied->id
        );

        $this->assertEquals(
            2,
            count( $contentService->loadVersions( $contentCopied->contentInfo ) )
        );

        $this->assertEquals( 2, $contentCopied->getVersionInfo()->versionNo );

        $this->assertAllFieldsEquals( $contentCopied->getFields() );
    }

    /**
     * Test for the copyContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::copyContent($contentInfo, $destinationLocationCreateStruct, $versionInfo)
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCopyContent
     * @todo Fix to more descriptive name
     */
    public function testCopyContentWithThirdParameter()
    {
        $parentLocationId = $this->generateId( 'location', 56 );

        $repository = $this->getRepository();

        $contentService = $repository->getContentService();
        $locationService = $repository->getLocationService();

        /* BEGIN: Use Case */
        $contentVersion2 = $this->createContentVersion2();

        // Configure new target location
        $targetLocationCreate = $locationService->newLocationCreateStruct( $parentLocationId );

        $targetLocationCreate->priority = 42;
        $targetLocationCreate->hidden = true;
        $targetLocationCreate->remoteId = '01234abcdef5678901234abcdef56789';
        $targetLocationCreate->sortField = Location::SORT_FIELD_NODE_ID;
        $targetLocationCreate->sortOrder = Location::SORT_ORDER_DESC;

        // Copy only the initial version
        $contentCopied = $contentService->copyContent(
            $contentVersion2->contentInfo,
            $targetLocationCreate,
            $contentService->loadVersionInfo( $contentVersion2->contentInfo, 1 )
        );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $contentCopied
        );

        $this->assertNotEquals(
            $contentVersion2->contentInfo->remoteId,
            $contentCopied->contentInfo->remoteId
        );

        $this->assertNotEquals(
            $contentVersion2->id,
            $contentCopied->id
        );

        $this->assertEquals(
            1,
            count( $contentService->loadVersions( $contentCopied->contentInfo ) )
        );

        $this->assertEquals( 1, $contentCopied->getVersionInfo()->versionNo );
    }

    /**
     * Test for the findContent() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\SearchResult
     * @see \eZ\Publish\API\Repository\ContentService::findContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testGetContentService
     */
    public function testFindContent()
    {
        self::markTestIncomplete( "Search have been moved to SearchService" );
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // Create a search query for content objects about "eZ Publish"
        $query = new Query();
        $query->criterion = new Criterion\LogicalAnd(
            array(
                new Criterion\Field( 'title', Criterion\Operator::LIKE, '*eZ Publish*' )
            )
        );

        // Search for matching content
        $searchResult = $contentService->findContent( $query, array() );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\SearchResult',
            $searchResult
        );

        return $searchResult;
    }

    /**
     * Test for the findContent() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\SearchResult $searchResult
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::findContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testFindContent
     */
    public function testFindContentSearchResultContainsExpectedProperties( $searchResult )
    {
        $this->assertGreaterThan( 0, $searchResult->count );
        $this->assertEquals( $searchResult->count, count( $searchResult->items ) );

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Query',
            $searchResult->query
        );
    }

    /**
     * Test for the findContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::findContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testFindContent
     */
    public function testFindContentWithLanguageFilter()
    {
        self::markTestIncomplete( "Search have been moved to SearchService" );
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createMultipleLanguageDraftVersion1();

        // Publish the newly created draft
        $contentService->publishVersion( $draft->getVersionInfo() );

        // Create a search query for the created content object
        $query = new Query();
        $query->criterion = new Criterion\LogicalAnd(
            array(
                new Criterion\Field( 'name', Criterion\Operator::LIKE, '*multi-lang forum²*' )
            )
        );

        // Search for matching content with field language filter
        $searchResult = $contentService->findContent(
            $query,
            array( 'languages' => array( 'eng-GB' ) )
        );
        /* END: Use Case */

        $this->assertEquals( 1, $searchResult->count );
        $this->assertLocaleFieldsEquals(
            $searchResult->items[0]->getFields(),
            'eng-GB'
        );
    }

    /**
     * Test for the findSingle() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::findSingle()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testFindSingle()
    {
        self::markTestIncomplete( "Search have been moved to SearchService" );
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentService = $repository->getContentService();

        // Create a search query for content objects about "eZ Publish"
        $query = new Query();
        $query->criterion = new Criterion\LogicalAnd(
            array(
                new Criterion\Field( 'title', Criterion\Operator::LIKE, '*Demo Design*' )
            )
        );

        // Search for a matching content object
        $content = $contentService->findSingle( $query, array() );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Content',
            $content
        );

        return $content;
    }

    /**
     * Test for the findSingle() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::findSingle()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testFindSingle
     */
    public function testFindSingleResultContainsExpectedProperties( $content )
    {
        $this->assertRegExp( '(Demo Design)i', $content->getFieldValue( 'title', 'eng-US' ) );
    }

    /**
     * Test for the findSingle() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::findSingle()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testFindSingle
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     */
    public function testFindSingleWithLanguageFilter()
    {
        self::markTestIncomplete( "Search have been moved to SearchService" );
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createMultipleLanguageDraftVersion1();

        // Publish the newly created draft
        $contentService->publishVersion( $draft->getVersionInfo() );

        // Create a search query for the created content object
        $query = new Query();
        $query->criterion = new Criterion\LogicalAnd(
            array(
                new Criterion\Field( 'name', Criterion\Operator::LIKE, '*multi-lang*' )
            )
        );

        // Search for matching content with field language filter
        $content = $contentService->findSingle(
            $query,
            array( 'languages' => array( 'eng-GB' ) )
        );
        /* END: Use Case */

        $this->assertLocaleFieldsEquals(
            $content->getFields(),
            'eng-GB'
        );
    }

    /**
     * Test for the findSingle() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::findSingle()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testFindSingle
     */
    public function testFindSingleThrowsNotFoundException()
    {
        self::markTestIncomplete( "Search have been moved to SearchService" );
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createMultipleLanguageDraftVersion1();

        // Publish the newly created draft
        $contentService->publishVersion( $draft->getVersionInfo() );

        // Create a search query for the created content object
        $query = new Query();
        $query->criterion = new Criterion\LogicalAnd(
            array(
                new Criterion\Field( 'title', Criterion\Operator::LIKE, 'TestingThisWithAnInvalidSearchQuery' )
            )
        );

        // This call will fail with an "NotFoundException", because the above
        // search query should not match against any object in an eZ Publish demo
        $contentService->findSingle( $query, array() );
        /* END: Use Case */
    }

    /**
     * Test for the findSingle() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::findSingle()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testFindSingle
     */
    public function testFindSingleThrowsInvalidArgumentException()
    {
        self::markTestIncomplete( "Search have been moved to SearchService" );
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $draft = $this->createMultipleLanguageDraftVersion1();

        // Publish the newly created draft
        $contentService->publishVersion( $draft->getVersionInfo() );

        // Create a search query for the created content object
        $query = new Query();
        $query->criterion = new Criterion\LogicalAnd(
            array(
                new Criterion\Field( 'name', Criterion\Operator::LIKE, '*a*' )
            )
        );

        // This call will fail with an "InvalidArgumentException", because the
        // above query would return more than one result
        $contentService->findSingle( $query, array() );
        /* END: Use Case */
    }

    /**
     * Test for the addRelation() method.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @see \eZ\Publish\API\Repository\ContentService::addRelation()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersionFromContentDraft
     */
    public function testAddRelation()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // RemoteId of the "Media" content of an eZ Publish demo installation
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';

        $draft = $this->createContentDraftVersion1();

        $media = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );

        // Create relation between new content object and "Media" page
        $relation = $contentService->addRelation(
            $draft->getVersionInfo(),
            $media
        );
        /* END: Use Case */

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Relation',
            $relation
        );

        return $contentService->loadContent( $draft->id );
    }

    /**
     * Test for the addRelation() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::addRelation()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testAddRelation
     */
    public function testAddRelationAddsRelationToContent( $content )
    {
        $this->assertEquals( 1, count( $content->getRelations() ) );
    }

    /**
     * Test for the addRelation() method.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::addRelation()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testAddRelation
     */
    public function testAddRelationSetsExpectedRelations( $content )
    {
        $relations = $content->getRelations();

        $this->assertEquals(
            array(
                'type' => Relation::COMMON,
                'sourceFieldDefinitionIdentifier' => null,
                'sourceContentInfo' => 'abcdef0123456789abcdef0123456789',
                'destinationContentInfo' => 'a6e35cbcb7cd6ae4b691f3eee30cd262',
            ),
            array(
                'type' => $relations[0]->type,
                'sourceFieldDefinitionIdentifier' => $relations[0]->sourceFieldDefinitionIdentifier,
                'sourceContentInfo' => $relations[0]->sourceContentInfo->remoteId,
                'destinationContentInfo' => $relations[0]->destinationContentInfo->remoteId,
            )
        );
    }

    /**
     * Test for the addRelation() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::addRelation()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testAddRelation
     */
    public function testAddRelationThrowsBadStateException()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // RemoteId of the "Media" page of an eZ Publish demo installation
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';

        $content = $this->createContentVersion1();

        $media = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );

        // This call will fail with a "BadStateException", because content is
        // published and not a draft.
        $contentService->addRelation(
            $content->getVersionInfo(),
            $media
        );
        /* END: Use Case */
    }

    /**
     * Test for the loadRelations() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadRelations()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testAddRelation
     */
    public function testLoadRelations()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // Remote ids of the "Media" and the "eZ Publish Demo Design ..." page
        // of a eZ Publish demo installation.
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';
        $demoDesignRemoteId = '8b8b22fe3c6061ed500fbd2b377b885f';

        $draft = $this->createContentDraftVersion1();

        // Load other content objects
        $media = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );
        $demoDesign = $contentService->loadContentInfoByRemoteId( $demoDesignRemoteId );

        // Create relation between new content object and "Media" page
        $contentService->addRelation(
            $draft->getVersionInfo(),
            $media
        );

        // Create another relation with the "Demo Design" page
        $contentService->addRelation(
            $draft->getVersionInfo(),
            $demoDesign
        );

        // Load all relations
        $relations = $contentService->loadRelations( $draft->getVersionInfo() );
        /* END: Use Case */

        usort( $relations, function( $rel1, $rel2 ) {
            return strcasecmp(
                $rel2->getDestinationContentInfo()->remoteId,
                $rel1->getDestinationContentInfo()->remoteId
            );
        } );

        $this->assertEquals(
            array(
                array(
                    'sourceContentInfo' => 'abcdef0123456789abcdef0123456789',
                    'destinationContentInfo' => 'a6e35cbcb7cd6ae4b691f3eee30cd262',
                ),
                array(
                    'sourceContentInfo' => 'abcdef0123456789abcdef0123456789',
                    'destinationContentInfo' => '8b8b22fe3c6061ed500fbd2b377b885f',
                )
            ),
            array(
                array(
                    'sourceContentInfo' => $relations[0]->sourceContentInfo->remoteId,
                    'destinationContentInfo' => $relations[0]->destinationContentInfo->remoteId,
                ),
                array(
                    'sourceContentInfo' => $relations[1]->sourceContentInfo->remoteId,
                    'destinationContentInfo' => $relations[1]->destinationContentInfo->remoteId,
                )
            )
        );
    }

    /**
     * Test for the loadReverseRelations() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::loadReverseRelations()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testAddRelation
     */
    public function testLoadReverseRelations()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // Remote ids of the "Media" and the "eZ Publish Demo Design ..." page
        // of a eZ Publish demo installation.
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';
        $demoDesignRemoteId = '8b8b22fe3c6061ed500fbd2b377b885f';

        $content = $this->createContentVersion1();

        // Create some drafts
        $mediaDraft = $contentService->createContentDraft(
            $contentService->loadContentInfoByRemoteId( $mediaRemoteId )
        );
        $demoDesignDraft = $contentService->createContentDraft(
            $contentService->loadContentInfoByRemoteId( $demoDesignRemoteId )
        );

        // Create relation between new content object and "Media" page
        $contentService->addRelation(
            $mediaDraft->getVersionInfo(),
            $content->contentInfo
        );

        // Create another relation with the "Demo Design" page
        $contentService->addRelation(
            $demoDesignDraft->getVersionInfo(),
            $content->contentInfo
        );

        // Load all relations
        $relations = $contentService->loadReverseRelations( $content->contentInfo );
        /* END: Use Case */

        usort( $relations, function( $rel1, $rel2 ) {
            return strcasecmp(
                $rel2->getSourceContentInfo()->remoteId,
                $rel1->getSourceContentInfo()->remoteId
            );
        } );

        $this->assertEquals(
            array(
                array(
                    'sourceContentInfo' => 'a6e35cbcb7cd6ae4b691f3eee30cd262',
                    'destinationContentInfo' => 'abcdef0123456789abcdef0123456789',
                ),
                array(
                    'sourceContentInfo' => '8b8b22fe3c6061ed500fbd2b377b885f',
                    'destinationContentInfo' => 'abcdef0123456789abcdef0123456789',
                )
            ),
            array(
                array(
                    'sourceContentInfo' => $relations[0]->sourceContentInfo->remoteId,
                    'destinationContentInfo' => $relations[0]->destinationContentInfo->remoteId,
                ),
                array(
                    'sourceContentInfo' => $relations[1]->sourceContentInfo->remoteId,
                    'destinationContentInfo' => $relations[1]->destinationContentInfo->remoteId,
                )
            )
        );
    }

    /**
     * Test for the deleteRelation() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteRelation()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadRelations
     */
    public function testDeleteRelation()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // Remote ids of the "Media" and the "Demo Design" page of a eZ Publish
        // demo installation.
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';
        $demoDesignRemoteId = '8b8b22fe3c6061ed500fbd2b377b885f';

        $draft = $this->createContentDraftVersion1();

        $media = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );
        $demoDesign = $contentService->loadContentInfoByRemoteId( $demoDesignRemoteId );

        // Establish some relations
        $contentService->addRelation( $draft->getVersionInfo(), $media );
        $contentService->addRelation( $draft->getVersionInfo(), $demoDesign );

        // Delete one of the currently created relations
        $contentService->deleteRelation( $draft->getVersionInfo(), $media );

        // The relations array now contains only one element
        $relations = $contentService->loadRelations( $draft->getVersionInfo() );
        /* END: Use Case */

        $this->assertEquals( 1, count( $relations ) );
    }

    /**
     * Test for the deleteRelation() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteRelation()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testDeleteRelation
     */
    public function testDeleteRelationThrowsBadStateException()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // RemoteId of the "Media" page of an eZ Publish demo installation
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';

        $content = $this->createContentVersion1();

        // Load the destination object
        $media = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );

        // Create a new draft
        $draftVersion2 = $contentService->createContentDraft( $content->contentInfo );

        // Add a relation
        $contentService->addRelation( $draftVersion2->getVersionInfo(), $media );

        // Publish new version
        $contentVersion2 = $contentService->publishVersion(
            $draftVersion2->getVersionInfo()
        );

        // This call will fail with a "BadStateException", because content is
        // published and not a draft.
        $contentService->deleteRelation(
            $contentVersion2->getVersionInfo(),
            $media
        );
        /* END: Use Case */
    }

    /**
     * Test for the deleteRelation() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteRelation()
     * @expectedException \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testDeleteRelation
     */
    public function testDeleteRelationThrowsInvalidArgumentException()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // RemoteId of the "Media" page of an eZ Publish demo installation
        $mediaRemoteId = 'a6e35cbcb7cd6ae4b691f3eee30cd262';

        $draft = $this->createContentDraftVersion1();

        // Load the destination object
        $media = $contentService->loadContentInfoByRemoteId( $mediaRemoteId );

        // This call will fail with a "InvalidArgumentException", because no
        // relation exists between $draft and $media.
        $contentService->deleteRelation(
            $draft->getVersionInfo(),
            $media
        );
        /* END: Use Case */
    }

    /**
     * Test for the createContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     */
    public function testCreateContentInTransactionWithRollback()
    {
        if ( $this->isVersion4() )
        {
            $this->markTestSkipped( "This test requires eZ Publish 5" );
        }

        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentTypeService = $repository->getContentTypeService();
        $contentService = $repository->getContentService();

        // Start a transaction
        $repository->beginTransaction();

        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        // Get a content create struct and set mandatory properties
        $contentCreate = $contentService->newContentCreateStruct( $contentType, 'eng-US' );
        $contentCreate->setField( 'name', 'Sindelfingen forum' );

        $contentCreate->remoteId = 'abcdef0123456789abcdef0123456789';
        $contentCreate->alwaysAvailable = true;

        // Create a new content object
        $contentId = $contentService->createContent( $contentCreate )->id;

        // Rollback all changes
        $repository->rollback();

        try
        {
            // This call will fail with a "NotFoundException"
            $contentService->loadContent( $contentId );
        }
        catch ( \eZ\Publish\API\Repository\Exceptions\NotFoundException $e )
        {
            // This is expected
            return;
        }
        /* END: Use Case */

        $this->fail( 'Content object still exists after rollback.' );
    }

    /**
     * Test for the createContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testCommit
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     */
    public function testCreateContentInTransactionWithCommit()
    {
        if ( $this->isVersion4() )
        {
            $this->markTestSkipped( "This test requires eZ Publish 5" );
        }

        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        $contentTypeService = $repository->getContentTypeService();
        $contentService = $repository->getContentService();

        // Start a transaction
        $repository->beginTransaction();

        $contentType = $contentTypeService->loadContentTypeByIdentifier( 'forum' );

        // Get a content create struct and set mandatory properties
        $contentCreate = $contentService->newContentCreateStruct( $contentType, 'eng-US' );
        $contentCreate->setField( 'name', 'Sindelfingen forum' );

        $contentCreate->remoteId = 'abcdef0123456789abcdef0123456789';
        $contentCreate->alwaysAvailable = true;

        // Create a new content object
        $contentId = $contentService->createContent( $contentCreate )->id;

        // Rollback all changes
        $repository->commit();

        // Load the new content object
        $content = $contentService->loadContent( $contentId );
        /* END: Use Case */

        $this->assertEquals( $contentId, $content->id );
    }

    /**
     * Test for the createContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent($contentCreateStruct, $locationCreateStructs)
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentWithLocationCreateParameterDoesNotCreateLocationImmediately
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentThrowsNotFoundException
     */
    public function testCreateContentWithLocationCreateParameterInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // Start a transaction
        $repository->beginTransaction();

        $draft = $this->createContentDraftVersion1();

        $contentId = $draft->id;

        // Roleback the transaction
        $repository->rollback();

        try
        {
            // This call will fail with a "NotFoundException"
            $contentService->loadContent( $contentId );
        }
        catch ( NotFoundException $e )
        {
            return;
        }
        /* END: Use Case */

        $this->fail( 'Can still load content object after rollback.' );
    }

    /**
     * Test for the createContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContent($contentCreateStruct, $locationCreateStructs)
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testCommit
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentWithLocationCreateParameterDoesNotCreateLocationImmediately
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentThrowsNotFoundException
     */
    public function testCreateContentWithLocationCreateParameterInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        // Start a transaction
        $repository->beginTransaction();

        $draft = $this->createContentDraftVersion1();

        $contentId = $draft->id;

        // Roleback the transaction
        $repository->commit();

        // Load the new content object
        $content = $contentService->loadContent( $contentId );
        /* END: Use Case */

        $this->assertEquals( $contentId, $content->id );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     */
    public function testCreateContentDraftInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Get the content service
        $contentService = $repository->getContentService();

        // Load the user group content object
        $content = $contentService->loadContent( $contentId );

        // Start a new transaction
        $repository->beginTransaction();

        // Create a new draft
        $drafted = $contentService->createContentDraft( $content->contentInfo );

        // Store version number for later reuse
        $versionNo = $drafted->versionInfo->versionNo;

        // Rollback
        $repository->rollback();

        try
        {
            // This call will fail with a "NotFoundException"
            $contentService->loadContent( $contentId, null, $versionNo );
        }
        catch ( NotFoundException $e )
        {
            return;
        }
        /* END: Use Case */

        $this->fail( 'Can still load content draft after rollback' );
    }

    /**
     * Test for the createContentDraft() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::createContentDraft()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testCommit
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContentDraft
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     */
    public function testCreateContentDraftInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Get the content service
        $contentService = $repository->getContentService();

        // Load the user group content object
        $content = $contentService->loadContent( $contentId );

        // Start a new transaction
        $repository->beginTransaction();

        // Create a new draft
        $drafted = $contentService->createContentDraft( $content->contentInfo );

        // Store version number for later reuse
        $versionNo = $drafted->versionInfo->versionNo;

        // Commit all changes
        $repository->commit();

        $content = $contentService->loadContent( $contentId, null, $versionNo );
        /* END: Use Case */

        $this->assertEquals(
            $versionNo,
            $content->getVersionInfo()->versionNo
        );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     */
    public function testPublishVersionInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Get the content service
        $contentService = $repository->getContentService();

        // Load the user group content object
        $content = $contentService->loadContent( $contentId );

        // Start a new transaction
        $repository->beginTransaction();

        $draftVersion = $contentService->createContentDraft( $content->contentInfo )->getVersionInfo();

        // Publish a new version
        $content = $contentService->publishVersion( $draftVersion );


        // Store version number for later reuse
        $versionNo = $content->versionInfo->versionNo;

        // Rollback
        $repository->rollback();

        try
        {
            // This call will fail with a "NotFoundException"
            $contentService->loadContent( $contentId, null, $versionNo );
        }
        catch ( NotFoundException $e )
        {
            return;
        }
        /* END: Use Case */

        $this->fail( 'Can still load content draft after rollback' );
    }

    /**
     * Test for the publishVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::publishVersion()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testCommit
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testPublishVersion
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadVersionInfo
     */
    public function testPublishVersionInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        /* BEGIN: Use Case */
        // ID of the "Administrator users" user group
        $contentId = 12;

        // Get the content service
        $contentService = $repository->getContentService();

        // Load the user group content object
        $template = $contentService->loadContent( $contentId );

        // Start a new transaction
        $repository->beginTransaction();

        // Publish a new version
        $content = $contentService->publishVersion(
            $contentService->createContentDraft( $template->contentInfo )->getVersionInfo()
        );

        // Store version number for later reuse
        $versionNo = $content->versionInfo->versionNo;

        // Commit all changes
        $repository->commit();

        // Load current version info
        $versionInfo = $contentService->loadVersionInfo( $content->contentInfo );
        /* END: Use Case */

        $this->assertEquals( $versionNo, $versionInfo->versionNo );
    }

    /**
     * Test for the updateContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testUpdateContentInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Load content service
        $contentService = $repository->getContentService();

        // Create a new user group draft
        $draft = $contentService->createContentDraft(
            $contentService->loadContentInfo( $contentId )
        );

        // Get an update struct and change the group name
        $contentUpdate = $contentService->newContentUpdateStruct();
        $contentUpdate->setField( 'name', 'Administrators', 'eng-US' );

        // Start a transaction
        $repository->beginTransaction();

        // Update the group name
        $draft = $contentService->updateContent(
            $draft->getVersionInfo(),
            $contentUpdate
        );

        // Publish updated version
        $contentService->publishVersion( $draft->getVersionInfo() );

        // Rollback all changes.
        $repository->rollback();

        // Name will still be "Administrator users"
        $name = $contentService->loadContent( $contentId )->getFieldValue( 'name' );
        /* END: Use Case */

        $this->assertEquals( 'Administrator users', $name );
    }

    /**
     * Test for the updateContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testCommit
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testUpdateContentInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Load content service
        $contentService = $repository->getContentService();

        // Create a new user group draft
        $draft = $contentService->createContentDraft(
            $contentService->loadContentInfo( $contentId )
        );

        // Get an update struct and change the group name
        $contentUpdate = $contentService->newContentUpdateStruct();
        $contentUpdate->setField( 'name', 'Administrators', 'eng-US' );

        // Start a transaction
        $repository->beginTransaction();

        // Update the group name
        $draft = $contentService->updateContent(
            $draft->getVersionInfo(),
            $contentUpdate
        );

        // Publish updated version
        $contentService->publishVersion( $draft->getVersionInfo() );

        // Commit all changes.
        $repository->commit();

        // Name is now "Administrators"
        $name = $contentService->loadContent( $contentId )->getFieldValue( 'name', 'eng-US' );
        /* END: Use Case */

        $this->assertEquals( 'Administrators', $name );
    }

    /**
     * Test for the updateContentMetadata() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContentMetadata()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContentMetadata
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testUpdateContentMetadataInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Get the content service
        $contentService = $repository->getContentService();

        // Load a ContentInfo object
        $contentInfo = $contentService->loadContentInfo( $contentId );

        // Store remoteId for later testing
        $remoteId = $contentInfo->remoteId;

        // Start a transaction
        $repository->beginTransaction();

        // Get metadata update struct and change remoteId
        $metadataUpdate = $contentService->newContentMetadataUpdateStruct();
        $metadataUpdate->remoteId = md5( microtime( true ) );

        // Update the metadata of the published content object
        $contentService->updateContentMetadata(
            $contentInfo,
            $metadataUpdate
        );

        // Rollback all changes.
        $repository->rollback();

        // Load current remoteId
        $remoteIdReloaded = $contentService->loadContentInfo( $contentId )->remoteId;
        /* END: Use Case */

        $this->assertEquals( $remoteId, $remoteIdReloaded );
    }

    /**
     * Test for the updateContentMetadata() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::updateContentMetadata()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testCommit
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testUpdateContentMetadata
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testUpdateContentMetadataInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Get the content service
        $contentService = $repository->getContentService();

        // Load a ContentInfo object
        $contentInfo = $contentService->loadContentInfo( $contentId );

        // Store remoteId for later testing
        $remoteId = $contentInfo->remoteId;

        // Start a transaction
        $repository->beginTransaction();

        // Get metadata update struct and change remoteId
        $metadataUpdate = $contentService->newContentMetadataUpdateStruct();
        $metadataUpdate->remoteId = md5( microtime( true ) );

        // Update the metadata of the published content object
        $contentService->updateContentMetadata(
            $contentInfo,
            $metadataUpdate
        );

        // Commit all changes.
        $repository->commit();

        // Load current remoteId
        $remoteIdReloaded = $contentService->loadContentInfo( $contentId )->remoteId;
        /* END: Use Case */

        $this->assertNotEquals( $remoteId, $remoteIdReloaded );
    }

    /**
     * Test for the deleteVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteVersion()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentDrafts
     */
    public function testDeleteVersionInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Get the content service
        $contentService = $repository->getContentService();

        // Start a new transaction
        $repository->beginTransaction();

        // Create a new draft
        $draft = $contentService->createContentDraft(
            $contentService->loadContentInfo( $contentId )
        );

        $contentService->deleteVersion( $draft->getVersionInfo() );

        // Rollback all changes.
        $repository->rollback();

        // This array will be empty
        $drafts = $contentService->loadContentDrafts();
        /* END: Use Case */

        $this->assertSame( array(), $drafts );
    }

    /**
     * Test for the deleteVersion() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteVersion()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testCommit
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCreateContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentDrafts
     */
    public function testDeleteVersionInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 12 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Administrator users" user group

        // Get the content service
        $contentService = $repository->getContentService();

        // Start a new transaction
        $repository->beginTransaction();

        // Create a new draft
        $draft = $contentService->createContentDraft(
            $contentService->loadContentInfo( $contentId )
        );

        $contentService->deleteVersion( $draft->getVersionInfo() );

        // Commit all changes.
        $repository->commit();

        // This array will contain no element
        $drafts = $contentService->loadContentDrafts();
        /* END: Use Case */

        $this->assertSame( array(), $drafts );
    }

    /**
     * Test for the deleteContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testDeleteContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testDeleteContentInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 11 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Members" user group in an eZ Publish
        // demo installation

        // Get content service
        $contentService = $repository->getContentService();

        // Load a ContentInfo instance
        $contentInfo = $contentService->loadContentInfo( $contentId );

        // Start a new transaction
        $repository->beginTransaction();

        // Delete content object
        $contentService->deleteContent( $contentInfo );

        // Rollback all changes
        $repository->rollback();

        // This call will return the original content object
        $contentInfo = $contentService->loadContentInfo( $contentId );
        /* END: Use Case */

        $this->assertEquals( $contentId, $contentInfo->id );
    }

    /**
     * Test for the deleteContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::deleteContent()
     * @depends eZ\Publish\API\Repository\Tests\RepositoryTest::testRollback
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testDeleteContent
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testLoadContentInfo
     */
    public function testDeleteContentInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 11 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Members" user group in an eZ Publish
        // demo installation

        // Get content service
        $contentService = $repository->getContentService();

        // Load a ContentInfo instance
        $contentInfo = $contentService->loadContentInfo( $contentId );

        // Start a new transaction
        $repository->beginTransaction();

        // Delete content object
        $contentService->deleteContent( $contentInfo );

        // Commit all changes
        $repository->commit();

        try
        {
            $contentService->loadContentInfo( $contentId );
        }
        catch ( NotFoundException $e )
        {
            return;
        }
        /* END: Use Case */

        $this->fail( 'Can still load ContentInfo after commit.' );
    }

    /**
     * Test for the copyContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::copyContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCopyContent
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testNewLocationCreateStruct
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testLoadLocationChildren
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testLoadLocation
     */
    public function testCopyContentInTransactionWithRollback()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 11 );
        $locationId = $this->generateId( 'location', 13 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Members" user group in an eZ Publish
        // demo installation

        // $locationId is the ID of the "Adminstrator users" group location

        // Get services
        $contentService = $repository->getContentService();
        $locationService = $repository->getLocationService();

        // Load content object to copy
        $content = $contentService->loadContent( $contentId );

        // Create new target location
        $locationCreate = $locationService->newLocationCreateStruct( $locationId );

        // Start a new transaction
        $repository->beginTransaction();

        // Copy content with all versions and drafts
        $contentService->copyContent(
            $content->contentInfo,
            $locationCreate
        );

        // Rollback all changes
        $repository->rollback();

        // This array will only contain a single admin user object
        $locations = $locationService->loadLocationChildren(
            $locationService->loadLocation( $locationId )
        );
        /* END: Use Case */

        $this->assertEquals( 1, count( $locations ) );
    }

    /**
     * Test for the copyContent() method.
     *
     * @return void
     * @see \eZ\Publish\API\Repository\ContentService::copyContent()
     * @depends eZ\Publish\API\Repository\Tests\ContentServiceTest::testCopyContent
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testNewLocationCreateStruct
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testLoadLocationChildren
     * @depend(s) eZ\Publish\API\Repository\Tests\LocationServiceTest::testLoadLocation
     */
    public function testCopyContentInTransactionWithCommit()
    {
        $repository = $this->getRepository();

        $contentId = $this->generateId( 'object', 11 );
        $locationId = $this->generateId( 'location', 13 );
        /* BEGIN: Use Case */
        // $contentId is the ID of the "Members" user group in an eZ Publish
        // demo installation

        // $locationId is the ID of the "Adminstrator users" group location

        // Get services
        $contentService = $repository->getContentService();
        $locationService = $repository->getLocationService();

        // Load content object to copy
        $content = $contentService->loadContent( $contentId );

        // Create new target location
        $locationCreate = $locationService->newLocationCreateStruct( $locationId );

        // Start a new transaction
        $repository->beginTransaction();

        // Copy content with all versions and drafts
        $contentCopied = $contentService->copyContent(
            $content->contentInfo,
            $locationCreate
        );

        // Commit all changes
        $repository->commit();

        // This will contain the admin user and the new child location
        $locations = $locationService->loadLocationChildren(
            $locationService->loadLocation( $locationId )
        );
        /* END: Use Case */

        $this->assertEquals( 2, count( $locations ) );
    }

    /**
     * @return void
     */
    public function testURLAliasesCreatedForNewContent()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();
        $locationService = $repository->getLocationService();
        $urlAliasService = $repository->getURLAliasService();

        /* BEGIN: Use Case */
        $draft = $this->createContentDraftVersion1();

        // Automatically creates a new URLAlias for the content
        $liveContent = $contentService->publishVersion( $draft->getVersionInfo() );
        /* END: Use Case */

        $location = $locationService->loadMainLocation(
            $liveContent->getVersionInfo()->getContentInfo()
        );

        $aliases = $urlAliasService->listLocationAliases( $location );

        $this->assertAliasesCorrect(
            array(
                '/Design/eZ-publish/An-awesome-forum' => array(
                    'type' => URLAlias::LOCATION,
                    'destination' => $location,
                    'path' => '/Design/eZ-publish/An-awesome-forum',
                    'languageCodes' => array( 'eng-US' ),
                    'isHistory' => false,
                    'isCustom' => false,
                    'forward' => false,
                ),
            ),
            $aliases
        );
    }

    /**
     * @return void
     */
    public function testURLAliasesCreatedForUpdatedContent()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();
        $locationService = $repository->getLocationService();
        $urlAliasService = $repository->getURLAliasService();

        /* BEGIN: Use Case */
        $draft = $this->createUpdatedDraftVersion2();

        // Automatically marks old aliases for the content as history
        // and creates new aliases, based on the changes
        $liveContent = $contentService->publishVersion( $draft->getVersionInfo() );
        /* END: Use Case */

        $location = $locationService->loadMainLocation(
            $liveContent->getVersionInfo()->getContentInfo()
        );

        $aliases = $urlAliasService->listLocationAliases( $location );

        $this->assertAliasesCorrect(
            array(
                '/Design/eZ-publish/An-awesome-forum' => array(
                    'type' => URLAlias::LOCATION,
                    'destination' => $location,
                    'path' => '/Design/eZ-publish/An-awesome-forum',
                    'languageCodes' => array( 'eng-US' ),
                    'isHistory' => true,
                    'isCustom' => false,
                    'forward' => false,
                ),
                '/Design/eZ-publish/An-awesome-forum²' => array(
                    'type' => URLAlias::LOCATION,
                    'destination' => $location,
                    'path' => '/Design/eZ-publish/An-awesome-forum²',
                    'languageCodes' => array( 'eng-US' ),
                    'isHistory' => false,
                    'isCustom' => false,
                    'forward' => false,
                ),
                '/Design/eZ-publish/An-awesome-forum²³' => array(
                    'type' => URLAlias::LOCATION,
                    'destination' => $location,
                    'path' => '/Design/eZ-publish/An-awesome-forum²³',
                    'languageCodes' => array( 'eng-GB' ),
                    'isHistory' => false,
                    'isCustom' => false,
                    'forward' => false,
                ),
            ),
            $aliases
        );
    }

    /**
     * @return void
     */
    public function testCustomURLAliasesNotHistorizedOnUpdatedContent()
    {
        $repository = $this->getRepository();

        $contentService = $repository->getContentService();

        /* BEGIN: Use Case */
        $urlAliasService = $repository->getURLAliasService();
        $locationService = $repository->getLocationService();

        $content = $this->createContentVersion1();

        // Create a custom URL alias
        $urlAliasService->createUrlAlias(
            $locationService->loadMainLocation( $content->getVersionInfo()->getContentInfo() ),
            '/my/fancy/story-about-ez-publish',
            'eng-US'
        );

        $draftVersion2 = $contentService->createContentDraft( $content->contentInfo );

        $contentUpdate = $contentService->newContentUpdateStruct();
        $contentUpdate->initialLanguageCode = 'eng-US';
        $contentUpdate->setField( 'name', 'Amazing Bielefeld forum' );

        $draftVersion2 = $contentService->updateContent(
            $draftVersion2->getVersionInfo(),
            $contentUpdate
        );

        // Only marks auto-generated aliases as history
        // the custom one is left untouched
        $liveContent = $contentService->publishVersion( $draftVersion2->getVersionInfo() );
        /* END: Use Case */

        $location = $locationService->loadMainLocation(
            $liveContent->getVersionInfo()->getContentInfo()
        );

        $aliases = $urlAliasService->listLocationAliases( $location );

        $this->assertAliasesCorrect(
            array(
                '/Design/eZ-publish/An-awesome-forum' => array(
                    'type' => URLAlias::LOCATION,
                    'destination' => $location,
                    'path' => '/Design/eZ-publish/An-awesome-forum',
                    'languageCodes' => array( 'eng-US' ),
                    'isHistory' => true,
                    'isCustom' => false,
                    'forward' => false,
                    'alwaysAvailable' => true,
                ),
                '/Design/eZ-publish/Amazing-Bielefeld-forum' => array(
                    'type' => URLAlias::LOCATION,
                    'destination' => $location,
                    'path' => '/Design/eZ-publish/Amazing-Bielefeld-forum',
                    'languageCodes' => array( 'eng-US' ),
                    'isHistory' => false,
                    'isCustom' => false,
                    'forward' => false,
                    'alwaysAvailable' => true,
                ),
                '/my/fancy/story-about-ez-publish' => array(
                    'type' => URLAlias::LOCATION,
                    'destination' => $location,
                    'path' => '/my/fancy/story-about-ez-publish',
                    'languageCodes' => array( 'eng-US' ),
                    'isHistory' => false,
                    'isCustom' => true,
                    'forward' => false,
                    'alwaysAvailable' => false,
                ),
            ),
            $aliases
        );
    }

    /**
     * Asserts that all aliases defined in $expectedAliasProperties with the
     * given properties are available in $actualAliases and not more.
     *
     * @param array $expectedAliasProperties
     * @param array $actualAliases
     * @return void
     */
    private function assertAliasesCorrect( array $expectedAliasProperties, array $actualAliases )
    {
        foreach ( $actualAliases as $actualAlias )
        {
            if ( !isset( $expectedAliasProperties[$actualAlias->path] ) )
            {
                $this->fail(
                    sprintf(
                        'Alias with path "%s" in languages "%s" not expected.',
                        $actualAlias->path,
                        implode( ', ', $actualAlias->languageCodes )
                    )
                );
            }

            foreach ( $expectedAliasProperties[$actualAlias->path] as $propertyName => $propertyValue )
            {
                $this->assertEquals(
                    $propertyValue,
                    $actualAlias->$propertyName,
                    sprintf(
                        'Property $%s incorrect on alias with path "%s" in languages "%s".',
                        $propertyName,
                        $actualAlias->path,
                        implode( ', ', $actualAlias->languageCodes )
                    )
                );
            }

            unset( $expectedAliasProperties[$actualAlias->path] );
        }

        if ( !count( $expectedAliasProperties ) === 0 )
        {
            $this->fail(
                sprintf(
                    'Missing expected aliases with paths "%s".',
                    implode( '", "', array_keys( $expectedAliasProperties ) )
                )
            );
        }
    }

    /**
     * Asserts that the given fields are equal to the default fields fixture.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field[] $fields
     * @return void
     */
    private function assertAllFieldsEquals( array $fields )
    {
        $actual = $this->normalizeFields( $fields );
        $expected = $this->normalizeFields( $this->createFieldsFixture() );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * Asserts that the given fields are equal to a language filtered set of the
     * default fields fixture.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field[] $fields
     * @param string $languageCode
     *
     * @return void
     */
    private function assertLocaleFieldsEquals( array $fields, $languageCode )
    {
        $actual = $this->normalizeFields( $fields );

        $expected = array();
        foreach ( $this->normalizeFields( $this->createFieldsFixture() ) as $field )
        {
            if ( $field->languageCode !== $languageCode )
            {
                continue;
            }
            $expected[] = $field;
        }

        $this->assertEquals( $expected, $actual );
    }

    /**
     * This method normalizes a set of fields and returns a normalized set.
     *
     * Normalization means it resets the storage specific field id to zero and
     * it sorts the field by their identifier and their language code. In
     * addition, the field value is removed, since this one depends on the
     * specific FieldType, which is tested in a dedicated integration test.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field[] $fields
     * @return \eZ\Publish\API\Repository\Values\Content\Field[]
     */
    private function normalizeFields( array $fields )
    {
        $normalized = array();
        foreach ( $fields as $field )
        {
            $normalized[] = new Field(
                array(
                    'id' => 0,
                    'value' => ( $field->value !== null ? true : null ),
                    'languageCode' => $field->languageCode,
                    'fieldDefIdentifier' => $field->fieldDefIdentifier
                )
            );
        }
        usort( $normalized, function ( $field1, $field2 ) {
            if ( 0 === ( $return = strcasecmp( $field1->fieldDefIdentifier, $field2->fieldDefIdentifier ) ) )
            {
                return strcasecmp( $field1->languageCode, $field2->languageCode );
            }
            return $return;
        } );

        return $normalized;
    }

    /**
     * Returns a filtered set of the default fields fixture.
     *
     * @param string $languageCode
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Field[]
     */
    private function createLocaleFieldsFixture( $languageCode )
    {
        $fields = array();
        foreach ( $this->createFieldsFixture() as $field )
        {
            if ( null === $field->languageCode || $languageCode === $field->languageCode )
            {
                $fields[] = $field;
            }
        }
        return $fields;
    }

    /**
     * Returns the default fixture of fields used in most tests.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Field[]
     */
    private function createFieldsFixture()
    {
        return array(
            new Field(
                array(
                    'id' => 0,
                    'value' => null,
                    'languageCode' => 'eng-US',
                    'fieldDefIdentifier' => 'description'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => null,
                    'languageCode' => 'eng-GB',
                    'fieldDefIdentifier' => 'description'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => 'An awesome multi-lang forum²',
                    'languageCode' => 'eng-US',
                    'fieldDefIdentifier' => 'name'
                )
            ),
            new Field(
                array(
                    'id' => 0,
                    'value' => 'An awesome multi-lang forum²³',
                    'languageCode' => 'eng-GB',
                    'fieldDefIdentifier' => 'name'
                )
            ),
        );
    }
}
