<?php
/**
 * File containing the BaseTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\REST;

$repository = new Client\Repository(
    new Client\HttpClient\Stream(
        'http://localhost:8042/'
    )
    // Mapper…
);

$repository->setCurrentUser(
    new \eZ\Publish\API\Repository\Tests\Stubs\Values\User\UserStub(
        array(
            'id' => 14,
            'content'  =>  new \eZ\Publish\API\Repository\Tests\Stubs\Values\Content\ContentStub(
                array(
                    'contentId' => 14
                )
            )
        )
    )
);

return $repository;
