<?php
use PHPUnit\Framework\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
 
class RequestHandlerSearchTest extends TestCase {

    public function testSearchApiErrorOrException(){

        require __DIR__ .'/../src/env.php';
        require __DIR__ .'/../src/search_handler.php';
        
        $mock = new MockHandler([
            new Response(404),
            new Response(500),
            new RequestException('Error Communicating with Server', new Request('GET', 'http://foo.bar'))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        ob_start();
        search_handler('http://foo.bar', $client);
        $output = json_decode(ob_get_clean());
        $this->assertSame(null, $output);

        ob_start();
        search_handler('http://foo.bar', $client);
        $output = json_decode(ob_get_clean());
        $this->assertSame(null, $output);

        ob_start();
        search_handler('http://foo.bar', $client);
        $output = json_decode(ob_get_clean());
        $this->assertSame(null, $output);
    }

    public function testSearch(){

        require __DIR__ .'/../src/env.php';
        require __DIR__ .'/../src/search_handler.php';
        
        $mock_reponse_body = json_encode([
            'data' => [
                [
                    'value' => 'foo',
                    'ngrams_hit' => 4,
                    'indexed_at' => 123456789
                ],
                [
                    'value' => 'fo',
                    'ngrams_hit' => 3,
                    'indexed_at' => 123456789
                ],
            ],
            'meta' => [],
            'links' => []
        ]);

        $mock = new MockHandler([
            new Response(
                200, 
                ['X-Foo' => 'Bar'], 
                $mock_reponse_body
            ),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        ob_start();
        search_handler('foo', $client);
        $output = json_decode(ob_get_clean());

        $expected = json_decode(json_encode([
            'data' => [
                [
                    'raw_value' => 'foo',
                    'ngrams_hit' => 4,
                    'html' => '<div>foo</div>'
                ],
                [
                    'raw_value' => 'fo',
                    'ngrams_hit' => 3,
                    'html' => '<div>fo</div>'
                ],
            ]
        ]));

        $this->assertEquals($expected, $output);
    }

}