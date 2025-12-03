<?php

use App\Services\BinderbyteService;

$key = config('services.binderbyte.key');
echo "API Key loaded: " . ($key ? substr($key, 0, 5) . '...' : 'NULL/EMPTY') . "\n";

$service = new BinderbyteService();
$provinces = $service->getProvinces();

echo "Provinces count: " . count($provinces) . "\n";
