<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('test-facial');
});

Route::get('/test-facial', function () {
    return view('test-facial');
});

Route::post('/test-facial', function (Request $request) {
    
    if (!$request->hasFile('foto_registro') || !$request->hasFile('foto_webcam')) {
        return back()->withErrors(['Faltan imágenes o superan el límite de PHP.']);
    }

    $url = env('FACIAL_SERVICE_URL', 'http://127.0.0.1:8181/verify');

try {
        $response = Http::timeout(60)
            ->attach('img1', $request->file('foto_registro')->get(), 'reg.jpg')
            ->attach('img2', $request->file('foto_webcam')->get(), 'web.jpg')
            ->post($url);

        if (!$response->successful()) {
            return back()->withErrors(['El microservicio de IA ha fallado. Detalles: ' . $response->body()]);
        }

        $datosPython = $response->json();
        return view('test-facial', ['resultado' => $datosPython]);

    } catch (Exception $e) {
        return back()->withErrors(['Error de conexión con Docker: ' . $e->getMessage()]);
    }
});