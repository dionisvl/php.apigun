<?php

require('vendor/autoload.php');

// WEBSOCKET SERVER START!
use dionisvl\apigun\domain\RatchetChat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new RatchetChat()
        )
    ), 8080
);
print_r('Wss started with this address: ' . $server->socket->getAddress());

$server->run();


return 1;