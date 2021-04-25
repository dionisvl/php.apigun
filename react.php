<?php
require('vendor/autoload.php');

error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface;

$loop = React\EventLoop\Factory::create();
$client = new React\Http\Browser($loop);
//$client->withTimeout(60.0);

$name = generateRandomString(4000000);
//print_r($name . PHP_EOL);
$post_body = http_build_query(['name' => $name, 'message' => 'sdfsdf']);


for ($i = 1; $i < 500; $i++) {
    $start = microtime(true);

    //https://swapi.dev/api/planets/3/?format=json

    $client->post('https://ya.ru',
        [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        $post_body
    )
//    $client->get('https://phpqa.ru')
        ->then(function (ResponseInterface $response) use ($start, $i) {
            //var_dump((string)$response->getBody());
            $status = (string)$response->getStatusCode();

            $end = microtime(true);
            $time = number_format(($end - $start), 4);
            print_r($i . ' - ' . $time . ' - ' . $status . PHP_EOL);
        });

}


$loop->run();


function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}