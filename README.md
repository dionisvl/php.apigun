# API tester

## Installation

### How to make nginx proxy for websockets
```
    location /wss/ {
        proxy_pass http://localhost:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
```


### Use case example (Windows platform):

- edit CHAT_* params in .env file  
  server and client ports must be same. Ideal for both - 8083  
  client scheme must be "ws"
- run server :

```
php artisan chat:start
or
php -q C:\OSpanel\domains\apigun\2-chat-server.php
```

- Open url:  
  http://apigunclient/
