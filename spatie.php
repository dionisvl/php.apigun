<?php

require('vendor/autoload.php');

use GuzzleHttp\Client;
use Spatie\Async\Pool;

$URL = 'https://swapi.dev/api/planets/3/?format=json';
$URL = 'https://ya.ru';

$pool = Pool::create();

$client = new Client(
    [
        // Base URI is used with relative requests
        'base_uri' => $URL,
        // You can set any number of default request options.
        'timeout' => 10.0,
    ]
);


//$client = new Client();
usleep(800);

for ($i = 1; $i <= 20; $i++) {
    $start = microtime(true);
    $pool->add(
        function () use ($client, $URL) {
            // Do a thing
            return $client->request('GET', $URL);
        }
    )->then(
        function ($response) use ($i, $start) {
            if (is_string($response)) {
                echo $response;
            } else {
                $status = (string)$response->getStatusCode();
                $end = microtime(true);
                $time = number_format(($end - $start), 4);
                echo($i . ' - ' . $time . ' - ' . $status . PHP_EOL);
            }
        }
    )->catch(
        function (Throwable $exception) {
            echo('Error: ' . $exception->getMessage());
        }
    );
}

$pool->wait();
