<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testImport()
    {
        $client = new Client();
        $promises = [
            'promise1' => $client->getAsync('http://pprocess-playground.local/'),
            'promise2' => $client->getAsync('http://pprocess-playground.local/'),
        ];

        Promise\unwrap($promises);
        $results = Promise\settle($promises)->wait();

        $this->assertEquals($results['promise1']['value']->getStatusCode(), 200);
        $this->assertEquals($results['promise2']['value']->getStatusCode(), 200);
    }
}