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
            'start' => 1415289600,
            'stop' => 1415295600
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
            'start' => 1415289600,
            'stop' => 1415295600,
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
        
        // Delete the program and check images are gone
        $client->request('DELETE', '/v1/programs/' . $program['id']);
        $this->assertTrue($client->getResponse()->isOk());
    }
}