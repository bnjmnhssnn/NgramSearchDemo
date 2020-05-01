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
        run('http://foo.bar', $client);
        $output = json_decode(ob_get_clean());
        $this->assertSame(null, $output);

        ob_start();
        run('http://foo.bar', $client);
        $output = json_decode(ob_get_clean());
        $this->assertSame(null, $output);

        ob_start();
        run('http://foo.bar', $client);
        $output = json_decode(ob_get_clean());
        $this->assertSame(null, $output);
    }

    public function testSearch(){

        require __DIR__ .'/../src/env.php';
        require __DIR__ .'/../src/search_handler.php';
        
        $mock_reponse_body = json_encode([
            'data' => [
                [
                    'id' => 123,
                    'key' => 'foo the movie',
                    'value' => 'Foo The Movie',
                    'ngrams_hit' => 4,
                    'ngram_details' => [
                        ['value' => ' f', 'pos_in_key' => 0, 'pos_in_search' => 0],
                        ['value' => 'fo', 'pos_in_key' => 1, 'pos_in_search' => 1],
                        ['value' => 'oo', 'pos_in_key' => 2, 'pos_in_search' => 2],
                        ['value' => 'o ', 'pos_in_key' => 3, 'pos_in_search' => 3],
                    ]
                ],
                [
                    'id' => 456,
                    'key' => 'the fool on the hill',
                    'value' => 'The Fool On The Hill',
                    'ngrams_hit' => 3,
                    'ngram_details' => [
                        ['value' => ' f', 'pos_in_key' => 4, 'pos_in_search' => 0],
                        ['value' => 'fo', 'pos_in_key' => 5, 'pos_in_search' => 1],
                        ['value' => 'oo', 'pos_in_key' => 6, 'pos_in_search' => 2],
                    ]
                ],
            ],
            'meta' => [
                'search_ngrams' => [' f', 'fo', 'oo', 'o '],
                'result_length' => 2,
                'duration' => 0.123,
                'peak_memory' => '1.23MB'
            ],
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
        run('foo', $client);
        $output = json_decode(ob_get_clean());
        $this->assertEquals(
            '<div class="result-box"><div>Foo The Movie</div><div>The Fool On The Hill</div></div>', 
            $output->html
        );
    }

}