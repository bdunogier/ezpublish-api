<?php
namespace ezp\PublicAPI\Values\Content\Query\SortClause;

use ezp\PublicAPI\Values\Content\Query,
    ezp\PublicAPI\Values\Content\Query\SortClause;

/**
 * Sets sort direction on the location path date for a content query
 */
class LocationPath extends SortClause
{
    /**
     * Constructs a new LocationPath SortClause
     * @param string $sortDirection
     */
    public function __construct( $sortDirection = Query::SORT_ASC )
    {
        parent::__construct( 'location_path', $sortDirection );
    }
}
