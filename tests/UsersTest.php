<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class UsersTest extends ApiTestCase {

    use RefreshDatabaseTrait;

    public function testeCreateUser(): void {
       // $response = static:createClient()->request('POST','')
    }

}
