<?php
/**
 * File containing the LanguageService class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST\Client;

use \eZ\Publish\API\Repository\Repository;
use \eZ\Publish\API\Repository\Values\Content\Language;
use \eZ\Publish\API\Repository\Values\Content\LanguageCreateStruct;


/**
 * Implementation of the {@link \eZ\Publish\API\Repository\LanguageService}
 * interface.
 *
 * @see \eZ\Publish\API\Repository\LanguageService
 */
class LanguageService implements \eZ\Publish\API\Repository\LanguageService
{
    /**
     * @var \eZ\Publish\API\REST\Client\Repository
     */
    private $repository;

    /**
     * @var \eZ\Publish\API\REST\Client\ContentService
     */
    private $contentService;

    /**
     * @var string
     */
    private $defaultLanguageCode;

    /**
     * Instantiates the language service.
     *
     * @param \eZ\Publish\API\REST\Client\Repository $repository
     * @param \eZ\Publish\API\REST\Client\ContentService $contentService
     * @param string $defaultLanguageCode
     */
    public function __construct(
        Repository $repository,
        ContentService $contentService,
        $defaultLanguageCode
    )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * Creates the a new Language in the content repository
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException if the languageCode already exists
     *
     * @param \eZ\Publish\API\Repository\Values\Content\LanguageCreateStruct $languageCreateStruct
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Language
     */
    public function createLanguage( LanguageCreateStruct $languageCreateStruct )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * Changes the name of the language in the content repository
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Language $language
     * @param string $newName
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Language
     */
    public function updateLanguageName( Language $language, $newName )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * enables a language
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Language $language
     */
    public function enableLanguage( Language $language )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * disables a language
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Language $language
     */
    public function disableLanguage( Language $language )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * Loads a Language from its language code ($languageCode)
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException if language could not be found
     *
     * @param string $languageCode
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Language
     */
    public function loadLanguage( $languageCode )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * Loads all Languages
     *
     * @return array an aray of {@link  \eZ\Publish\API\Repository\Values\Content\Language}
     */
    public function loadLanguages()
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * Loads a Language by its id ($languageId)
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException if language could not be found
     *
     * @param int $languageId
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Language
     */
    public function loadLanguageById( $languageId )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * Deletes  a language from content repository
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     *         if language can not be deleted
     *         because it is still assigned to some content / type / (...).
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Language $language
     */
    public function deleteLanguage( Language $language )
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * returns a configured default language code
     *
     * @return string
     */
    public function getDefaultLanguageCode()
    {
        throw new \Exception( "@TODO: Implement." );
    }

    /**
     * instanciates an object to be used for creating languages
     * 
     * @return \eZ\Publish\API\Repository\Values\Content\LanguageCreateStruct
     */
    public function newLanguageCreateStruct()
    {
        throw new \Exception( "@TODO: Implement." );
    }
}
