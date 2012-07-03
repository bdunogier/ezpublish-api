<?php
/**
 * File containing the Test Setup Factory base class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests\SetupFactory;

use eZ\Publish\Core\Persistence\Solr;

/**
 * A Test Factory is used to setup the infrastructure for a tests, based on a
 * specific repository implementation to test.
 */
class LegacySolr extends Legacy
{
    protected static $indexed = false;

    /**
     * Returns a configured repository for testing.
     *
     * @return \eZ\Publish\API\Repository\Repository
     */
    public function getRepository()
    {
        $repository = parent::getRepository();

        // @HACK: This is a hack to inject a different search handler -- is
        // there a well supported way to do this? I don't think so.
        $persistenceProperty = new \ReflectionProperty( $repository, 'persistenceHandler' );
        $persistenceProperty->setAccessible( true );
        $persistenceHandler = $persistenceProperty->getValue( $repository );

        $searchProperty = new \ReflectionProperty( $persistenceHandler, 'searchHandler' );
        $searchProperty->setAccessible( true );
        $searchProperty->setValue(
            $persistenceHandler,
            $this->getSearchHandler( $persistenceHandler )
        );

        return $repository;
    }

    protected function getSearchHandler( $persistenceHandler )
    {
        $contentMapperMethod = new \ReflectionMethod( $persistenceHandler, 'getContentMapper' );
        $contentMapperMethod->setAccessible( true );

        $fieldHandlerMethod = new \ReflectionMethod( $persistenceHandler, 'getFieldHandler' );
        $fieldHandlerMethod->setAccessible( true );

        $searchHandler = new Solr\Content\Search\Handler(
            new Solr\Content\Search\Gateway\Native(
                new Solr\Content\Search\Gateway\HttpClient\Stream( getenv( "solrServer" ) )
            ),
            $contentMapperMethod->invoke( $persistenceHandler ),
            $fieldHandlerMethod->invoke( $persistenceHandler )
        );

        $this->indexAll( $persistenceHandler, $searchHandler );

        return $searchHandler;
    }

    protected function indexAll( $persistenceHandler, $searchHandler )
    {
        if ( self::$indexed )
        {
            return;
        }

        // @TODO: Is there a nicer way to get access to all content objects? We 
        // require this to run a full index here.
        $getDatabaseMethod = new \ReflectionMethod( $persistenceHandler, 'getDatabase' );
        $getDatabaseMethod->setAccessible( true );
        $db = $getDatabaseMethod->invoke( $persistenceHandler );

        $query = $db->createSelectQuery()
            ->select( 'id', 'current_version' )
            ->from( 'ezcontentobject' );

        $stmt = $query->prepare();
        $stmt->execute();

        while ( $row = $stmt->fetch( \PDO::FETCH_ASSOC ) )
        {
            $searchHandler->indexContent(
                $persistenceHandler->contentHandler()->load( $row['id'], $row['current_version'] )
            );
        }

        self::$indexed = true;
    }
}
