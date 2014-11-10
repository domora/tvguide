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
        
        $client = $this->createAuthenticatedClient();
        
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
        $client = $this->createAuthenticatedClient();

        $wrongProgramId = 'fake-id';
        $client->request('DELETE', '/v1/programs/' . $wrongProgramId);
        $this->assertFalse($client->getResponse()->isOk());
    }
    
    public function testPostNeedCredentials()
    {
        $client = $this->createClient();
        
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([]));
        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
    }
    
    public function testCreateProgramWithImage()
    {
        $client = $this->createAuthenticatedClient();
        
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
        $client = $this->createAuthenticatedClient();

        // Create a program
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c'),
            'episode' => ['season' => 1, 'episode' => 42],
            'desc' => 'Test'
        ]));
        
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $json = json_decode($response->getContent(), true);
        $this->assertEquals(1, $json['data']['season']);
        $this->assertEquals(42, $json['data']['episode']);
        $this->assertEquals('Test', $json['data']['desc']);
    }

    public function testCreateProgramInWrongChannel()
    {
        $client = $this->createAuthenticatedClient();

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

        $client = $this->createAuthenticatedClient();

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
        $client = $this->createAuthenticatedClient();
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
        $client = $this->createAuthenticatedClient();
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode([
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c'),
            'credits' => [
                'actors' => [['name' => 'Person 0'], ['name' => 'Person 1'], ['name' => 'Unknown Person']],
                'writers' => []
            ]
        ]));
        
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $json = json_decode($response->getContent(), true);
        $program = $json['data'];
        
        $this->assertCount(2, $program['credits']);
        $this->assertCount(3, $program['credits']['actors']);
        $this->assertEquals('Person 0', $program['credits']['actors'][0]['name']);
        $this->assertEquals('Person 1', $program['credits']['actors'][1]['name']);
        $this->assertEquals('Unknown Person', $program['credits']['actors'][2]['name']);
        $this->assertNotEmpty($program['credits']['actors'][0]['description']);
        $this->assertNotEmpty($program['credits']['actors'][1]['description']);
        $this->assertNotContains('description', $program['credits']['actors'][2]);
    }
    
    public function testCreateDuplicateProgram()
    {
        $client = $this->createAuthenticatedClient();
        $program = [
            'title' => 'Test Program',
            'start' => date('c', time() - 3600),
            'stop' => date('c')
        ];
        
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode($program));
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        
        $client->request('POST', '/v1/channels/fr-ch1/programs', [], [], ['content_type' => 'application/json'], json_encode($program));
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
        $this->assertEquals(409, $response['code']);
        $this->assertEquals('PROGRAM_CONFLICT', $response['status']);
    }
}