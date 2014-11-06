<?php

namespace Domora\Tests\TvGuide;

use Domora\Tests\WebTestCase;

class ChannelControllerTest extends WebTestCase
{
    public function testGetChannel()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/v1/channels');

        $this->assertTrue($client->getResponse()->isOk());
    }
}