<?php

use App\Services\BinderbyteService;

$service = new BinderbyteService();
$provinces = $service->getProvinces();

echo "Provinces count: " . count($provinces) . "\n";
if (count($provinces) > 0) {
    echo "Sample province: " . json_encode($provinces[0]) . "\n";
    echo "\nFirst 5 provinces:\n";
    foreach (array_slice($provinces, 0, 5) as $prov) {
        echo "- ID: {$prov['id']}, Name: {$prov['name']}\n";
    }
}

// Test getting cities for DKI Jakarta (ID: 31)
echo "\n\nTesting getCities for DKI Jakarta (ID: 31):\n";
try {
    $cities = $service->getCities('31');
    echo "Cities count: " . count($cities) . "\n";
    if (count($cities) > 0) {
        echo "First city: " . json_encode($cities[0]) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
