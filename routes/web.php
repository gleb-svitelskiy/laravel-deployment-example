<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', function () {
    return phpinfo();
});

Route::get('/request', function (Request $request) {
    dump($request->fullUrl());
    dump($request->host());
    dump($request->httpHost());
    dump($request->schemeAndHttpHost());
    dump($request->header());
    dump('ip', $request->ip());
    dump('ips', $request->ips());
    dump($request);
});
