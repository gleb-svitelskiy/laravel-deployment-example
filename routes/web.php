<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/log', function () {
    logger('Debug message');
});

Route::get('/phpinfo', function () {
    return phpinfo();
});

Route::get('/request', function (Request $request) {
    dump('fullUrl', $request->fullUrl());
    dump('host', $request->host());
    dump('httpHost', $request->httpHost());
    dump('schemeAndHttpHost', $request->schemeAndHttpHost());
    dump('header', $request->header());
    dump('ip', $request->ip());
    dump('ips', $request->ips());
    dump($request);
});

Route::get('/cache-set', function () {
    cache(['key' => 'value']);
});
Route::get('/cache-get', function () {
    return cache('key');
});
Route::get('/session-set', function () {
    session(['key' => time()]);
});
Route::get('/session-get', function () {
    return session('key');
});
Route::get('/hostname', function () {
    return gethostname();
});
