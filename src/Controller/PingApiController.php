<?php

namespace Controller;

use Core\Request;
use Core\Response;

class PingApiController
{
    public function ping(Request $request, Response $response): void {
        $response->json(['ok' => true, 'message' => 'pong']);
    }    
}
