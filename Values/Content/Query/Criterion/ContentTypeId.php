<?php
namespace ezp\PublicAPI\Values\Content\Query\Criterion;
use ezp\PublicAPI\Values\Content\Query\Criterion,
    ezp\PublicAPI\Values\Content\Query\Criterion\Operator\Specifications,
    ezp\PublicAPI\Values\Content\Query\CriterionInterface;

/**
 * A criterion that matches content based on its ContentType id
 *
 * Supported operators:
 * - IN: will match from a list of ContentTypeId
 * - EQ: will match against one ContentTypeId
 */
class ContentTypeId extends Criterion implements CriterionInterface
{
    /**
     * Creates a new ContentType criterion
     *
     * Content will be matched if it matches one of the contentTypeId in $value
     *
     * @param integer|array(integer) One or more content Id that must be matched
     *
     * @throws InvalidArgumentException if a non numeric id is given
     * @throws InvalidArgumentException if the value type doesn't match the operator
     */
    public function __construct( $value )
    {
        parent::__construct( null, null, $value );
    }

    public function getSpecifications()
    {
        $types = Specifications::TYPE_INTEGER | Specifications::TYPE_STRING;
        return array(
            new Specifications( Operator::IN, Specifications::FORMAT_ARRAY, $types ),
            new Specifications( Operator::EQ, Specifications::FORMAT_SINGLE, $types ),
        );
    }

    public static function createFromQueryBuilder( $target, $operator, $value )
    {
        return new self( $value );
    }
}
