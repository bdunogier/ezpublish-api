<?php
namespace ezp\PublicAPI\Interfaces;

use ezp\PublicAPI\Values\IO\BinaryFile;

/**
 * The io service for managing binary files
 *
 * @package ezp\PublicAPI\Interfaces
 *
 */
interface IOService
{
    /**
     * Creates a BinaryFileCreateStruct object from the uploaded file $uploadedFile
     * 
     * @throws \ezp\PublicAPI\Exceptions\InvalidArgumentException When given an invalid uploaded file
     *
     * @param array $uploadedFile The $_POST hash of an uploaded file
     * 
     * @return \ezp\PublicAPI\Values\IO\BinaryFileCreateStruct
     */
    public function newBinaryCreateStructFromUploadedFile( array $uploadedFile );

    /**
     * Creates a BinaryFileCreateStruct object from $localFile
     * 
     * @throws \ezp\PublicAPI\Exceptions\InvalidArgumentException When given a non existing / unreadable file
     *
     * @param string $localFile Path to local file
     * 
     * @return \ezp\PublicAPI\Values\IO\BinaryFileCreateStruct
     */
    public function newBinaryCreateStructFromLocalFile( $localFile );

    /**
     * Creates a  binary file in the the repository
     *
     * @param \ezp\PublicAPI\Values\IO\BinaryFileCreateStruct $binaryFileCreateStruct
     * 
     * @return \ezp\PublicAPI\Values\IO\BinaryFile The created BinaryFile object
     */
    public function createBinaryFile( BinaryFileCreateStruct $binaryFileCreateStruct );
        
    /**
     * Deletes the BinaryFile with $path
     *
     * @param BinaryFile $binaryFile
     */
    public function deleteBinaryFile( BinaryFile $binaryFile );

    /**
     * Loads the binary file with $id
     * 
     * @throws \ezp\PublicAPI\Exceptions\NotFoundExcption
     *
     * @param string $binaryFileid
     * 
     * @return \ezp\PublicAPI\Values\IO\BinaryFile
     */
    public function loadBinaryFile( $binaryFileid );
    
 
     /**
     * Returns a read (mode: rb) file resource to the binary file identified by $path
     *
     * @param BinaryFile $binaryFile
     *
     * @return resource
     */
    public function getFileInputStream( BinaryFile $binaryFile );

    /**
     * Returns the content of the binary file
     *
     * @param BinaryFile $binaryFile
     *
     * @return string
     */
    public function getFileContents( BinaryFile $binaryFile );
}
