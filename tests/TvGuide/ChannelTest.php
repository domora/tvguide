<?php

namespace Domora\Tests\TvGuide;

use Domora\Tests\WebTestCase;

class ChannelControllerTest extends WebTestCase
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

    public function testCreateProgramInChannel()
    {
        $client = $this->createClient();

        // Create a program
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => 1415289600,
            'stop' => 1415295600
        ]));
        
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
    }

    public function testCreateProgramInWrongChannel()
    {
        $client = $this->createClient();

        // Create a program
        $client->request('POST', '/v1/channels/fake-id/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => 1415289600,
            'stop' => 1415295600
        ]));
        
        $response = $client->getResponse();
        $this->assertFalse($response->isOk());
    }

    public function testCreateWrongProgramInChannel()
    {
        $this->markTestSkipped('todo : handle errors in channelController.');

        $client = $this->createClient();

        // Fail to create wrong programs
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
        ]));
        $this->assertFalse($client->getResponse()->isOk());

        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'stop' => 1415295600
        ]));
        $this->assertFalse($client->getResponse()->isOk());

        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => 1415289600
        ]));
        $this->assertFalse($client->getResponse()->isOk());
    }
}