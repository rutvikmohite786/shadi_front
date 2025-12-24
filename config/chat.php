<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Socket Server URL
    |--------------------------------------------------------------------------
    |
    | The URL of the Node.js Socket.IO server for real-time chat functionality.
    |
    */
    'socket_url' => env('SOCKET_URL', 'http://localhost:3001'),
    
    /*
    |--------------------------------------------------------------------------
    | Socket Server Port
    |--------------------------------------------------------------------------
    |
    | The port on which the Socket.IO server is running.
    |
    */
    'socket_port' => env('SOCKET_PORT', 3001),
];

