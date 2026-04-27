<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/database/costing/1', 'DELETE');
// mock auth
$user = App\Models\User::first();
$app['auth']->login($user);
$response = $kernel->handle($request);
echo $response->getStatusCode() . "\n" . $response->getContent();
