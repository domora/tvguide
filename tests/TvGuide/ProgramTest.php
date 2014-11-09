<?php

namespace Domora\Tests\TvGuide;

use Domora\Tests\WebTestCase;

class ProgramTest extends WebTestCase
{

    public function testGetVoidProgramsList()
    {
        $client = $this->createClient();
        
        // Check the programs list
        $client->request('GET', '/v1/programs?channels=fr-ch1,fr-ch2&start=now&end=+1 hour');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $json = json_decode($response->getContent(), true);
        $this->assertCount(2, $json['channels']);
    }

    public function testCreateGetRemoveProgram()
    {
        $this->markTestSkipped('Try to avoid cache issues');
        
        $client = $this->createClient();
        
        // Create a program
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c')
        ]));
        
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $json = json_decode($response->getContent(), true);
        $this->assertEquals($json['code'], 200);
        $this->assertEquals($json['status'], 'PROGRAM_CREATED');
            
        // Check the newly created program
        $program = $json['data'];
        $client->request('GET', '/v1/programs/' . $program['id']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        // And delete it
        $client->request('DELETE', '/v1/programs/' . $program['id']);
        $this->assertTrue($client->getResponse()->isOk());
        
        // Check that it has been deleted
        $client->request('GET', '/v1/programs/' . $program['id']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetWrongProgram()
    {
        $client = $this->createClient();

        $wrongProgramId = 'fake-id';
        $client->request('GET', '/v1/programs/' . $wrongProgramId);
        $this->assertFalse($client->getResponse()->isOk());
    }

    public function testDeleteWrongProgram()
    {
        $client = $this->createClient();

        $wrongProgramId = 'fake-id';
        $client->request('DELETE', '/v1/programs/' . $wrongProgramId);
        $this->assertFalse($client->getResponse()->isOk());
    }
    
    public function testCreateProgramWithImage()
    {
        $client = $this->createClient();
        
        // Create a program
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c'),
            'image' => sprintf('%s/../Data/domora.jpg', dirname(__FILE__))
        ]));
        
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $json = json_decode($response->getContent(), true);
        $this->assertEquals($json['code'], 200);
        $this->assertEquals($json['status'], 'PROGRAM_CREATED');
        
        // Check that this program has images
        $program = $json['data'];
        $this->assertCount(3, $program['images']);
    }
    
    public function testCreateProgramInChannel()
    {
        $client = $this->createClient();

        // Create a program
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c')
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
            'start' => date('c', time() - 3600),
            'stop' => date('c')
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
            'stop' => date('c')
        ]));
        $this->assertFalse($client->getResponse()->isOk());

        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c')
        ]));
        $this->assertFalse($client->getResponse()->isOk());
    }
    
    public function testCreateProgramWithCredits()
    {
        $client = $this->createClient();
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c'),
            'credits' => [
                'actors' => [
                    ['name' => 'Pierre de Beaucorps']
                ]
            ]
        ]));
        
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $json = json_decode($response->getContent(), true);
        $program = $json['data'];
        
        $this->assertCount(1, $program['credits']);
        $this->assertCount(1, $program['credits']['actors']);
        $this->assertEquals('Pierre de Beaucorps', $program['credits']['actors'][0]['name']);
    }
    
    public function testCreateProgramWithExistingActor()
    {
        $client = $this->createClient();
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c'),
            'credits' => [
                'actors' => [
                    ['name' => 'Person 0']
                ]
            ]
        ]));
        
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $json = json_decode($response->getContent(), true);
        $program = $json['data'];
        
        $this->assertCount(1, $program['credits']);
        $this->assertCount(1, $program['credits']['actors']);
        $this->assertEquals('Person 0', $program['credits']['actors'][0]['name']);
        $this->assertNotEmpty($program['credits']['actors'][0]['description']);
    }
}