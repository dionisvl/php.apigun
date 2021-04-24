<?php

namespace dionisvl\apigun\domain;

use Psr\Http\Message\ResponseInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\EventLoop\Factory;
use React\Http\Browser;

// (C) CHAT CLASS
class Apigun implements MessageComponentInterface
{
    // (C1) PROPERTIES
    protected $clients; // Debug mode
    private $debug = true; // Connect clients

    // (C2) CONSTRUCTOR - INIT LIST OF CLIENTS

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        if ($this->debug) {
            echo "Chat server started.\r\n";
        }
    }

    private function gun($msg){
        $loop = Factory::create();
        $client = new Browser($loop);

        $client->get('http://www.google.com/')->then(function (ResponseInterface $response) {
            var_dump($response->getHeaders(), (string)$response->getBody());

            $msg['m'] = str_split((string)$response->getBody(),500);
            $msg = json_encode($msg, JSON_THROW_ON_ERROR);
            foreach ($this->clients as $client) {
                $client->send($msg);
            }
        });

        $loop->run();
    }

    // (C6) ON RECEIVING MESSAGE FROM CLIENT - SEND TO EVERYONE
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        if ($this->debug) {
            echo "Received message from {$from->resourceId}: {$msg}\r\n";
        }

        $msg = json_decode($msg, true, 512, JSON_THROW_ON_ERROR);
        $msg['datetime'] = date('Y-m-d H:i:s');

        $this->gun($msg);



        $msg = json_encode($msg, JSON_THROW_ON_ERROR);

        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }


    // (C3) ON CLIENT CONNECT - STORE INTO $THIS->CLIENTS
    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        if ($this->debug) {
            echo "Client connected: {$conn->resourceId}\r\n";
        }
    }

    // (C4) ON CLIENT DISCONNECT - REMOVE FROM $THIS->CLIENTS
    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        if ($this->debug) {
            echo "Client disconnected: {$conn->resourceId}\r\n";
        }
    }

    // (C5) ON ERROR
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $conn->close();
        if ($this->debug) {
            echo "Client error: {$conn->resourceId} | {$e->getMessage()}\r\n";
        }
    }

}
