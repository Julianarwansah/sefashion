<?php

use Illuminate\Support\Facades\Route;
use App\Services\BinderbyteService;

Route::get('/test-binderbyte', function (BinderbyteService $service) {
    $provinces = $service->getProvinces();

    return response()->json([
        'api_key' => config('services.binderbyte.key'),
        'provinces_count' => count($provinces),
        'provinces' => $provinces,
    ]);
});
