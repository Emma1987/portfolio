<?php

namespace App\Tests\Controller\Admin;

use App\Tests\AdminWebTestCase;

class AdminControllerTest extends AdminWebTestCase
{
    public function testRenderProjectsResponseIsSuccessful()
    {
        $this->client->request('GET', '/backstage/projects');
        $this->assertResponseIsSuccessful();
    }
}
