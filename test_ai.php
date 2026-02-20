<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/api/ai-support/chat', 'POST', [
    'message' => 'Oii, comprei a corrida, qual o status?',
    'history' => []
]);
$response = $kernel->handle($request);
echo $response->getContent();
