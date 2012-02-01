<?php
/**
 * @package ezp\PublicAPI\Interfaces
 */
namespace ezp\PublicAPI\Interfaces;

use ezp\PublicAPI\Values\User\UserCreateStruct;
use ezp\PublicAPI\Values\User\UserUpdateStruct;
use ezp\PublicAPI\Values\User\User;
use ezp\PublicAPI\Values\User\UserGroup;
use ezp\PublicAPI\Values\User\UserGroupCreateStruct;
use ezp\PublicAPI\Values\User\UserGroupUpdateStruct;

/**
 * This service provides methods for managing users and user groups
 *
 * @example Examples/user.php
 *
 * @package ezp\PublicAPI\Interfaces
 */
interface UserService
{
    /**
     * Creates a new user group using the data provided in the ContentCreateStruct parameter
     *
     * In 4.x in the content type parameter in the profile is ignored
     * - the content type is determined via configuration and can be set to null.
     * The returned version is published.
     *
     * @param \ezp\PublicAPI\Values\User\UserGroupCreateStruct $userGroupCreateStruct a structure for setting all necessary data to create this user group
     * @param \ezp\PublicAPI\Values\User\UserGroup $parentGroup
     *
     * @return \ezp\PublicAPI\Values\User\UserGroup
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     * @throws \ezp\PublicAPI\Exceptions\IllegalArgumentException if the input structure has invalid data
     * @throws \ezp\PublicAPI\Exceptions\ContentFieldValidationException if a field in the $userGroupCreateStruct is not valid
     * @throws \ezp\PublicAPI\Exceptions\ContentValidationException if a required field is missing
     */
    public function createUserGroup( UserGroupCreateStruct $userGroupCreateStruct, UserGroup $parentGroup );

    /**
     * Loads a user group for the given id
     *
     * @param int $id
     *
     * @return \ezp\PublicAPI\Values\User\UserGroup
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException if the user group with the given id was not found
     */
    public function loadUserGroup( $id );

    /**
     * Loads the sub groups of a user group
     *
     * @param \ezp\PublicAPI\Values\User\UserGroup $userGroup
     *
     * @return array an array of {@link \ezp\PublicAPI\Values\User\UserGroup}
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to read the user group
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException if the user group with the given id was not found
     */
    public function loadSubUserGroups( UserGroup $userGroup );

    /**
     * Removes a user group
     *
     * the users which are not assigned to other groups will be deleted.
     *
     * @param \ezp\PublicAPI\Values\User\UserGroup $userGroup
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException if the user group with the given id was not found
     */
    public function deleteUserGroup( UserGroup $userGroup );

    /**
     * Moves the user group to another parent
     *
     * @param \ezp\PublicAPI\Values\User\UserGroup $userGroup
     * @param \ezp\PublicAPI\Values\User\UserGroup $newParent
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException if the user group with the given id was not found
     */
    public function moveUserGroup( UserGroup $userGroup, UserGroup $newParent );

    /**
     * Updates the group profile with fields and meta data
     *
     * 4.x: If the versionUpdateStruct is set in $userGroupUpdateStruct, this method internally creates a content draft, updates ts with the provided data
     * and publishes the draft. If a draft is explititely required, the user group can be updated via the content service methods.
     *
     * @param \ezp\PublicAPI\Values\User\UserGroup $userGroup
     * @param \ezp\PublicAPI\Values\User\UserGroupUpdateStruct $userGroupUpdateStruct
     *
     * @return \ezp\PublicAPI\Values\User\UserGroup
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     * @throws \ezp\PublicAPI\Exceptions\ContentFieldValidationException if a field in the $userGroupUpdateStruct is not valid
     * @throws \ezp\PublicAPI\Exceptions\ContentValidationException if a required field is set empty
     */
    public function updateUserGroup( UserGroup $userGroup, UserGroupUpdateStruct $userGroupUpdateStruct );

    /**
     * Create a new user. The created user is published by this method
     *
     * @param \ezp\PublicAPI\Values\User\UserCreateStruct $userCreateStruct the data used for creating the user
     * @param array $parentGroups the groups of type {@link \ezp\PublicAPI\Values\User\UserGroup} which are assigned to the user after creation
     *
     * @return \ezp\PublicAPI\Values\User\User
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException if a user group was not found
     * @throws \ezp\PublicAPI\Exceptions\ContentFieldValidationException if a field in the $userCreateStruct is not valid
     * @throws \ezp\PublicAPI\Exceptions\ContentValidationException if a required field is missing
     */
    public function createUser( UserCreateStruct $userCreateStruct, array $parentGroups );

    /**
     * Loads a user
     *
     * @param integer $userId
     *
     * @return \ezp\PublicAPI\Values\User\User
     *
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException if a user with the given id was not found
     */
    public function loadUser( $userId );

    /**
     * Loads a user for the given login and password
     *
     * @param string $login
     * @param string $password the plain password
     *
     * @return \ezp\PublicAPI\Values\User\User
     *
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException if a user with the given credentials was not found
     */
    public function loadUserByCredentials( $login, $password );

    /**
     * This method deletes a user
     *
     * @param \ezp\PublicAPI\Values\User\User $user
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to delete the user
     */
    public function deleteUser( User $user );

    /**
     * Updates a user
     *
     * 4.x: If the versionUpdateStruct is set in the user update structure, this method internally creates a content draft, updates ts with the provided data
     * and publishes the draft. If a draft is explititely required, the user group can be updated via the content service methods.
     *
     * @param \ezp\PublicAPI\Values\User\User $user
     * @param \ezp\PublicAPI\Values\User\UserUpdateStruct
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to update the user
     * @throws \ezp\PublicAPI\Exceptions\ContentFieldValidationException if a field in the $userUpdateStruct is not valid
     * @throws \ezp\PublicAPI\Exceptions\ContentValidationException if a required field is set empty
     */
    public function updateUser( User $user, UserUpdateStruct $userUpdateStruct );

    /**
     * Assigns a new user group to the user
     *
     * If the user is already in the given user group this method does nothing.
     *
     * @param \ezp\PublicAPI\Values\User\User $user
     * @param \ezp\PublicAPI\Values\User\UserGroup $userGroup
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to assign the user group to the user
     */
    public function assignUserToUserGroup( User $user, UserGroup $userGroup );

    /**
     * Removes a user group from the user
     *
     * @param \ezp\PublicAPI\Values\User\User $user
     * @param \ezp\PublicAPI\Values\User\UserGroup $userGroup
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the authenticated user is not allowed to remove the user group from the user
     * @throws \ezp\PublicAPI\Exceptions\IllegalArgumentException if the user is not in the given user group
     */
    public function unAssignUssrFromUserGroup( User $user, UserGroup $userGroup );

    /**
     * Instantiate a user create class
     *
     * @param string $login the login of the new user
     * @param string $email the email of the new user
     * @param string $password the plain password of the new user
     * @param string $mainLanguageCode the main language for the underlying content object
     * @param ContentType $contentType 5.x the content type for the underlying content object. In 4.x it is ignored and taken from the configuration
     *
     * @return \ezp\PublicAPI\Values\User\UserCreateStruct
     */
    public function newUserCreateStruct( $login, $email, $password, $mainLanguageCode, $contentType = null );

    /**
     * Instantiate a user group create class
     *
     * @param string $mainLanguageCode The main language for the underlying content object
     * @param null|\ezp\PublicAPI\Values\ContentType\ContentType $contentType 5.x the content type for the underlying content object. In 4.x it is ignored and taken from the configuration
     *
     * @return \ezp\PublicAPI\Values\User\UserGroupCreateStruct
     */
    public function newUserGroupCreateStruct( $mainLanguageCode, $contentType = null );

    /**
     * Instantiate a new user update struct
     *
     * @return \ezp\PublicAPI\Values\User\UserUpdateStruct
     */
    public function newUserUpdateStruct();

    /**
     * Instantiate a new user group update struct
     *
     * @return \ezp\PublicAPI\Values\User\UserGroupUpdateStruct
     */
    public function newUserGroupUpdateStruct();
}
