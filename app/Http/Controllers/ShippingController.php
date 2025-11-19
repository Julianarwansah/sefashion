<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Get all provinces
     */
    public function getProvinces(): JsonResponse
    {
        try {
            $provinces = $this->rajaOngkir->getProvinces();
            
            return response()->json([
                'success' => !empty($provinces),
                'data' => $provinces ?? []
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch provinces',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request): JsonResponse
    {
        $request->validate([
            'province_id' => 'required|string'
        ]);

        try {
            $cities = $this->rajaOngkir->getCities($request->province_id);
            
            return response()->json([
                'success' => !empty($cities),
                'data' => $cities ?? []
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request): JsonResponse
    {
        $request->validate([
            'destination_city_id' => 'required|string',
            'weight' => 'required|numeric|min:1',
            'courier' => 'sometimes|string|in:jne,tiki,pos'
        ]);

        try {
            $destination = $request->destination_city_id;
            $weight = $request->weight;
            $courier = $request->courier ?? 'jne';

            if ($courier === 'all') {
                $shippingCosts = $this->rajaOngkir->getMultipleCosts($destination, $weight);
            } else {
                $costData = $this->rajaOngkir->getCost($destination, $weight, $courier);
                $shippingCosts = $costData ? [$courier => $this->rajaOngkir->formatCosts($costData)] : [];
            }

            return response()->json([
                'success' => !empty($shippingCosts),
                'data' => $shippingCosts,
                'origin' => $this->rajaOngkir->getOrigin()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate shipping cost',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get store location info
     */
    public function getStoreLocation(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->rajaOngkir->getOrigin()
        ]);
    }
}