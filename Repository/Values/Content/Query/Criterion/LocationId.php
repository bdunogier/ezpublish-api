<?php
namespace ezp\PublicAPI\Values\Content\Query\Criterion;
use ezp\PublicAPI\Values\Content\Query\Criterion,
    ezp\PublicAPI\Values\Content\Query\Criterion\Operator\Specifications,
    ezp\PublicAPI\Values\Content\Query\CriterionInterface;

/**
 * A criterion that matches content based on its own location id
 *
 * Parent location id is done using {@see ParentLocationId}
 *
 * Supported operators:
 * - IN: matches against a list of location ids
 * - EQ: matches against a unique location id
 */
class LocationId extends Criterion implements CriterionInterface
{
    /**
     * Creates a new LocationId criterion
     *
     * @param null $target Not used
     * @param string $operator
     *        Possible values:
     *        - Operator::IN: match against a list of locationId. $value must be an array of locationId
     *        - Operator::EQ: match against a single locationId. $value must be a single locationId
     * @param integer|array(integer) One or more locationId that must be matched
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
        return array(
            new Specifications(
                Operator::IN,
                Specifications::FORMAT_ARRAY,
                Specifications::TYPE_INTEGER | Specifications::TYPE_STRING
            ),
            new Specifications(
                Operator::EQ,
                Specifications::FORMAT_SINGLE,
                Specifications::TYPE_INTEGER | Specifications::TYPE_STRING
            ),
        );
    }

    public static function createFromQueryBuilder( $target, $operator, $value )
    {
        return new self( $value );
    }
}
