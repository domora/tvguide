<?php

namespace Domora\Tests\TvGuide;

use Domora\Tests\WebTestCase;

class ChannelTest extends WebTestCase
{
    public function testGetChannelsList()
    {
        $client = $this->createClient();
        $client->request('GET', '/v1/channels');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $json = json_decode($response->getContent(), true);
        $this->assertTrue(is_array($json));
    }

    public function testGetChannel()
    {
        $client = $this->createClient();

        $channelId = 'fr-ch1';
        $client->request('GET', '/v1/channels/' . $channelId);

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $json = json_decode($response->getContent(), true);
        $this->assertTrue(is_array($json));

        $this->assertEquals($channelId, $json['id']);
    }

    public function testGetWrongChannel()
    {
        $client = $this->createClient();

        $wrongChannelId = 'fake-id';
        $client->request('GET', '/v1/channels/' . $wrongChannelId);
        $this->assertFalse($client->getResponse()->isOk());
    }
}