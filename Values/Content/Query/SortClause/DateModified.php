<?php
namespace ezp\PublicAPI\Values\Content\Query\SortClause;

use ezp\PublicAPI\Values\Content\Query,
    ezp\PublicAPI\Values\Content\Query\SortClause;

/**
 * Sets sort direction on the content modification date for a content query
 */
class DateModified extends SortClause
{
    /**
     * Constructs a new DateCreated SortClause
     * @param string $sortDirection
     */
    public function __construct( $sortDirection = Query::SORT_ASC )
    {
        parent::__construct( 'date_modified', $sortDirection );
    }
}
