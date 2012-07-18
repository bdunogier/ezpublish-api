<?php
/**
 * File contains: eZ\Publish\Core\Persistence\Legacy\Tests\RepositoryTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests\FieldType;
use eZ\Publish\API\Repository,
    eZ\Publish\Core\FieldType\Url\Value as UrlValue,
    eZ\Publish\API\Repository\Values\Content\Field;

/**
 * Integration test for use field type
 *
 * @group integration
 * @group field-type
 */
class UrlFieldTypeIntergrationTest extends BaseIntegrationTest
{
    /**
     * Get name of tested field tyoe
     *
     * @return string
     */
    public function getTypeName()
    {
        return 'ezurl';
    }

    /**
     * Get a valid $fieldSettings value
     *
     * @return mixed
     * @TODO Implement correctly
     */
    public function getValidFieldSettings()
    {
        return null;
    }

    /**
     * Get a valid $validatorConfiguration
     *
     * @return mixed
     * @TODO Implement correctly
     */
    public function getValidValidatorConfiguration()
    {
        return null;
    }

    /**
     * Get $fieldSettings value not accepted by the field type
     *
     * @return mixed
     * @TODO Implement correctly
     */
    public function getInvalidFieldSettings()
    {
        return false;
    }

    /**
     * Get $validatorConfiguration not accepted by the field type
     *
     * @return mixed
     * @TODO Implement correctly
     */
    public function getInvalidValidatorConfiguration()
    {
        return false;
    }

    /**
     * Get initial field data for valid object creation
     *
     * @return mixed
     */
    public function getValidCreationFieldData()
    {
        return new UrlValue( 'http://example.com', 'Example' );
    }

    /**
     * Asserts that the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()}
     * was stored and loaded correctly.
     *
     * @param Field $field
     * @return void
     */
    public function assertFieldDataLoadedCorrect( Field $field)
    {
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\FieldType\\Url\\Value',
            $field->value
        );

        $expectedData = array(
            'link' => 'http://example.com',
            'text' => 'Example',
        );
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );
    }

    /**
     * Get update field externals data
     *
     * @return array
     */
    public function getValidUpdateFieldData()
    {
        return new UrlValue( 'http://example.com/2', 'Example  2' );
    }

    /**
     * Get externals updated field data values
     *
     * This is a PHPUnit data provider
     *
     * @return array
     */
    public function assertUpdatedFieldDataLoadedCorrect( Field $field )
    {
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\FieldType\\Url\\Value',
            $field->value
        );

        $expectedData = array(
            'link' => 'http://example.com/2',
            'text' => 'Example  2',
        );
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );
    }

    /**
     * Asserts the the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()}
     * was copied and loaded correctly.
     *
     * @param Field $field
     */
    public function assertCopiedFieldDataLoadedCorrectly( Field $field )
    {
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\FieldType\\Url\\Value',
            $field->value
        );

        $expectedData = array(
            'link' => 'http://example.com',
            'text' => 'Example',
        );
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );
    }

    /**
     * Get expectation for the toHash call on our field value
     *
     * @return mixed
     */
    public function getToHashExpectation()
    {
        return array(
            'link' => 'http://example.com',
            'text' => 'Example'
        );
    }

    /**
     * Get expectations for the fromHash call on our field value
     *
     * This is a PHPUnit data provider
     *
     * @return array
     */
    public function provideFromHashData()
    {
        return array(
            array(
                array( 'link' => 'http://example.com/sindelfingen' ),
                new UrlValue( 'http://example.com/sindelfingen' )
            ),
            array(
                array( 'link' => 'http://example.com/sindelfingen', 'text' => 'Foo' ),
                new UrlValue( 'http://example.com/sindelfingen', 'Foo' )
            )
        );
    }
}

