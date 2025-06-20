<?php

namespace App\Tests\Controller;

use App\Tests\AdminWebTestCase;

class AdminControllerTest extends AdminWebTestCase
{
    public function testRenderProjectsResponseIsSuccessful()
    {
        $this->client->request('GET', '/backstage/projects');
        $this->assertResponseIsSuccessful();
    }
}
