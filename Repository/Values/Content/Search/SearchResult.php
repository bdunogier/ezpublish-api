<?php
/**
 * File containing the eZ\Publish\API\Repository\Values\Content\Search\SearchResult class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Values\Content\Search;
/**
 * This class represents a search result
 *
 */
class SearchResult {

    /**
     * The facets for this search
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Search\Facet[]
     */
    public $facets;

    /**
     * The value objects found for the query
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Search\SearchHit[]
     */
    public $searchHits;

    /**
     * If spellcheck is on this field contains a collated query suggestion where in the aprropriate
     * criterions the wrong spelled value is replaced by a corrected one (TBD).
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Query\Criterion
     */
    public $spellSuggestion;

    /**
     * The duration of the search procesing in ms
     *
     * @var int
     */
    public $time;

    /**
     * Indicates if the search has timed out
     *
     * @var boolean
     */
    public $timedOut;

    /**
     * The maximum score of this query
     *
     * @var float
     */
    public $maxScore;

    /**
     * The total number of searchHits
     *
     * @var int
     */
    public $totalCount;

}