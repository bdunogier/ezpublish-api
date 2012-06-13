<?php
/**
 * File containing the eZ\Publish\API\Repository\Tests\Stubs\ObjectStateServiceStub class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package eZ\Publish\API\Repository
 */


namespace eZ\Publish\API\Repository\Tests\Stubs;

use eZ\Publish\API\Repository\ObjectStateService;

use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateUpdateStruct;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateCreateStruct;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroup;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectState;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct;

use eZ\Publish\API\Repository\Tests\Stubs\Exceptions;

/**
 * ObjectStateServiceStub
 */
class ObjectStateServiceStub implements ObjectStateService
{
    /**
     * Contains the next object state group ID
     *
     * @var int
     */
    private $nextGroupId = 0;

    /**
     * Object state groups
     *
     * @var \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroup[]
     */
    private $groups = array();

    /**
     * Contains the next object state ID
     *
     * @var int
     */
    private $nextStateId = 0;

    /**
     * Object states
     *
     * @var \eZ\Publish\API\Repository\Values\ObjectState\ObjectState[]
     */
    private $states = array();

    /**
     * Maps groups to contained states
     *
     * @var array
     */
    private $groupStateMap = array();

    /**;
     * Maps content object IDs to their state per group.
     *
     * <code>
     *  array(
     *      <objectId> => array(
     *          <groupId> => <stateId>,
     *          // ...
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @var array
     */
    private $objectStateMap = array();

    /**
     * @var \eZ\Publish\API\Repository\Tests\Stubs\RepositoryStub
     */
    private $repository;

    /**
     * Instantiates a new content type service stub.
     *
     * @param \eZ\Publish\API\Repository\Tests\Stubs\RepositoryStub $repository
     */
    public function __construct( RepositoryStub $repository )
    {
        $this->repository = $repository;

        $this->initFromFixture();
    }

     /**
     * creates a new object state group
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create an object state group
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct $objectStateGroupCreateStruct
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function createObjectStateGroup( ObjectStateGroupCreateStruct $objectStateGroupCreateStruct )
    {
        $groupData = array();
        foreach ( $objectStateGroupCreateStruct as $propertyName => $propertyValue )
        {
            $groupData[$propertyName] = $propertyValue;
        }

        $groupData['languageCodes'] = $this->determineLanguageCodes(
            $objectStateGroupCreateStruct->names,
            $objectStateGroupCreateStruct->descriptions
        );

        $groupData['id'] = $this->nextGroupId++;

        $group = new Values\ObjectState\ObjectStateGroupStub( $groupData );

        $this->groups[$group->id] = $group;

        return $group;
    }

    /**
     * Determines all available language codes from $names and $descriptions
     *
     * @param string[] $names
     * @param string[] $descriptions
     * @return string[]
     */
    protected function determineLanguageCodes( $names, $descriptions )
    {
        return array_unique( array_keys( array_merge(
            $names ?: array(),
            $descriptions ?: array()
        ) ) );
    }

    /**
     * Loads a object state group
     *
     * @param mixed $objectStateGroupId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException if the group was not found
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function loadObjectStateGroup( $objectStateGroupId )
    {
        if ( !isset( $this->groups[$objectStateGroupId] ) )
        {
            throw new Exceptions\NotFoundExceptionStub( '@TODO: What error code should be used?' );
        }
        return $this->groups[$objectStateGroupId];
    }


    /**
     * Loads all object state groups
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup[]
     */
    public function loadObjectStateGroups( $offset = 0, $limit = -1 )
    {
        return array_slice(
            array_values( $this->groups ),
            $offset,
            ( $limit == -1 ? null : $limit )
        );
    }

    /**
     * This method returns the ordered list of object states of a group
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectState[]
     */
    public function loadObjectStates( ObjectStateGroup $objectStateGroup )
    {
        $states = array();
        foreach ( $this->groupStateMap[$objectStateGroup->id] as $stateId )
        {
            $states[] = $this->states[$stateId];
        }
        return $states;
    }

    /**
     * updates an object state group
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update an object state group
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct $objectStateGroupUpdateStruct
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function updateObjectStateGroup( ObjectStateGroup $objectStateGroup, ObjectStateGroupUpdateStruct $objectStateGroupUpdateStruct )
    {
        $data = array(
            'id'                  => $objectStateGroup->id,
            'identifier'          => $objectStateGroup->identifier,
            'defaultLanguageCode' => $objectStateGroup->defaultLanguageCode,
            'names'               => $objectStateGroup->getNames(),
            'descriptions'        => $objectStateGroup->getDescriptions(),
        );

        foreach ( $objectStateGroupUpdateStruct as $propertyName => $propertyValue )
        {
            if ( $propertyValue !== null )
            {
                $data[$propertyName] = $propertyValue;
            }
        }

        $data['languageCodes'] = $this->determineLanguageCodes(
            $data['names'], $data['descriptions']
        );

        $this->groups[$objectStateGroup->id] = new Values\ObjectState\ObjectStateGroupStub( $data );

        return $this->groups[$objectStateGroup->id];
    }

    /**
     * Deletes a object state group including all states and links to content
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete an object state group
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     */
    public function deleteObjectStateGroup( ObjectStateGroup $objectStateGroup )
    {
        throw new \RuntimeException( "Not implemented, yet." );
    }

    /**
     * creates a new object state in the given group.
     *
     * Note: in current kernel: If it is the first state all content objects will
     * set to this state.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create an object state
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateCreateStruct $objectStateCreateStruct
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectState
     */
    public function createObjectState( ObjectStateGroup $objectStateGroup, ObjectStateCreateStruct $objectStateCreateStruct )
    {
        $stateData = array();
        foreach ( $objectStateCreateStruct as $propertyName => $propertyValue )
        {
            $stateData[$propertyName] = $propertyValue;
        }
        $stateData['id'] = $this->nextStateId++;
        $stateData['languageCodes'] = $this->determineLanguageCodes(
            $stateData['names'],
            $stateData['descriptions']
        );
        $stateData['stateGroup'] = $objectStateGroup;

        return $this->createObjectStateFromArray( $stateData );
    }

    /**
     * Creates and sets an object state from $stateData.
     *
     * @param array $stateData
     * @return \eZ\Publish\API\Repository\Values\ObjectState\ObjectState
     */
    protected function createObjectStateFromArray( array $stateData )
    {
        $newState = new Values\ObjectState\ObjectStateStub( $stateData );

        $this->states[$newState->id] = $newState;
        $this->groupStateMap[$newState->getObjectStateGroup()->id][] = $newState->id;

        return $newState;
    }

    /**
     * Loads an object state
     *
     * @param $stateId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException if the state was not found
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectState
     */
    public function loadObjectState( $stateId )
    {
        if ( !isset( $this->states[$stateId] ) )
        {
            throw new Exceptions\NotFoundExceptionStub( '@TODO: What error code should be used?' );
        }
        return $this->states[$stateId];
    }

    /**
     * updates an object state
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update an object state
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateUpdateStruct $objectStateUpdateStruct
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectState
     */
    public function updateObjectState( ObjectState $objectState, ObjectStateUpdateStruct $objectStateUpdateStruct )
    {
        $stateData = array(
            'id'                  => $objectState->id,
            'identifier'          => $objectState->identifier,
            'priority'            => $objectState->priority,
            'defaultLanguageCode' => $objectState->defaultLanguageCode,
            'names'               => $objectState->names,
            'descriptions'        => $objectState->descriptions,
            'stateGroup'          => $objectState->stateGroup,
        );

        foreach ( $objectStateUpdateStruct as $propertyName => $propertyValue )
        {
            if ( $propertyValue !== null )
            {
                $stateData[$propertyName] = $propertyValue;
            }
        }
        $stateData['languageCodes'] = $this->determineLanguageCodes(
            $stateData['names'],
            $stateData['descriptions']
        );

        return $this->createObjectStateFromArray( $stateData );
    }

    /**
     * changes the priority of the state
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to change priority on an object state
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectState $objectState
     * @param int $priority
     */
    public function setPriorityOfObjectState( ObjectState $objectState, $priority )
    {
        $objectState->_setPriority( $priority );
    }

    /**
     * Deletes a object state. The state of the content objects is reset to the
     * first object state in the group.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete an object state
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectState $objectState
     */
    public function deleteObjectState( ObjectState $objectState )
    {
        throw new \RuntimeException( "Not implemented, yet." );
    }


    /**
     * Sets the object-state of a state group to $state for the given content.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentExceptioon if the object state does not belong to the given group
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to change the object state
     *
     * @param \eZ\Publish\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectState $objectState
     *
     */
    public function setObjectState( ContentInfo $contentInfo, ObjectStateGroup $objectStateGroup, ObjectState $objectState )
    {
        throw new \RuntimeException( "Not implemented, yet." );
    }

    /**
     * Gets the object-state of object identified by $contentId.
     *
     * The $state is the id of the state within one group.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     *
     * @return \ez\Publish\API\Repository\Values\ObjectState\ObjectState
     */
    public function getObjectState( ContentInfo $contentInfo, ObjectStateGroup $objectStateGroup )
    {
        throw new \RuntimeException( "Not implemented, yet." );
    }

    /**
     * returns the number of objects which are in this state
     *
     * @param \ez\Publish\API\Repository\Values\ObjectState\ObjectState $objectState
     *
     * @return int
     */
    public function getContentCount( ObjectState $objectState )
    {
        throw new \RuntimeException( "Not implemented, yet." );
    }

    /**
     * Instantiates a new Object State Group Create Struct and sets $identified in it.
     *
     * @param string $identifier
     * @return \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct
     */
    public function newObjectStateGroupCreateStruct( $identifier )
    {
        return new ObjectStateGroupCreateStruct(
            array( 'identifier' => $identifier )
        );
    }

    /**
     * Instantiates a new Object State Group Update Struct.
     *
     * @return \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct
     */
    public function newObjectStateGroupUpdateStruct()
    {
        return new ObjectStateGroupUpdateStruct();
    }

    /**
     * Instantiates a new Object State Create Struct and sets $identifier in it.
     *
     * @param string $identifier
     * @return \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateCreateStruct
     */
    public function newObjectStateCreateStruct( $identifier )
    {
        return new ObjectStateCreateStruct(
            array( 'identifier' => $identifier )
        );
    }

    /**
     * Instantiates a new Object State Update Struct and sets $identifier in it.
     *
     * @return \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateUpdateStruct
     */
    public function newObjectStateUpdateStruct()
    {
        return new ObjectStateUpdateStruct();
    }

    /**
     * Helper method that initializes some default data from an existing legacy
     * test fixture.
     *
     * @return void
     */
    private function initFromFixture()
    {
        $this->groups = array();

        list(
            $this->groups,
            $this->nextGroupId
        ) = $this->repository->loadFixture( 'ObjectStateGroup' );

        ++$this->nextGroupId;

        list(
            $this->states,
            $this->groupStateMap,
            $this->objectStateMap,
            $this->nextStateId
        ) = $this->repository->loadFixture( 'ObjectState', array( 'groups' => $this->groups ) );

        ++$this->nextStateId;
    }
}
