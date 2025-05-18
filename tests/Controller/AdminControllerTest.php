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

    public function testRenderToolBoxResponseIsSuccessful()
    {
        $this->client->request('GET', '/backstage/toolbox');
        $this->assertResponseIsSuccessful();
    }
}
