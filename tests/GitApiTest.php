<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GitApiTest extends WebTestCase
{
    public function test(): void
    {
        $client = static::createClient();

        $firstRepo = 'symfony/symfony';
        $secondRepo = 'laravel/laravel';

        $client->request('GET', '/api', [
            'first' => $firstRepo,
            'second' => $secondRepo,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(isset($responseContent['first']));
        $this->assertTrue(isset($responseContent['second']));

        $this->assertTrue($responseContent['first']['fullname'] == $firstRepo);
        $this->assertTrue($responseContent['second']['fullname'] == $secondRepo);


    }
}
