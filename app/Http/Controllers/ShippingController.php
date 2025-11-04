<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;


class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Show shipping calculation form
     */
    public function showShippingForm()
    {
        $customers = Customer::whereNotNull('alamat')->get();
        $couriers = $this->rajaOngkir->getAvailableCouriers();
        
        // Default origin city (ganti dengan kota toko Anda)
        $defaultOriginCityId = env('RAJAONGKIR_ORIGIN_CITY_ID', 152); // Jakarta Pusat

        return view('admin.shipping.calculate', compact('customers', 'couriers', 'defaultOriginCityId'));
    }

    public function calculate()
    {
        // Gunakan cache untuk customers
        $customers = Cache::remember('customers_for_shipping', now()->addHours(6), function () {
            return Customer::whereNotNull('alamat')
                         ->select('id_customer', 'nama', 'email', 'alamat')
                         ->get();
        });

        // Data couriers dan provinces sudah di-cache di service
        $couriers = $this->rajaOngkir->getAvailableCouriers();
        $provinces = $this->rajaOngkir->getProvinces();
        
        $defaultOriginCityId = env('RAJAONGKIR_ORIGIN_CITY_ID', 152);
        
        // Cache origin city lookup
        $defaultOriginCity = Cache::remember('origin_city_' . $defaultOriginCityId, now()->addDays(7), function () use ($defaultOriginCityId) {
            return $this->rajaOngkir->getCity($defaultOriginCityId);
        });

        return view('calculate', compact(
            'customers', 
            'couriers', 
            'provinces',
            'defaultOriginCityId',
            'defaultOriginCity'
        ));
    }

    /**
     * Calculate shipping cost dengan validasi lebih cepat
     */
    public function calculateShippingCost(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id_customer',
            'courier' => 'required|in:jne,pos,tiki',
            'weight' => 'required|numeric|min:1|max:30000',
            'origin_city_id' => 'required|numeric',
        ]);

        // Cek cache dulu sebelum hit API
        $cacheKey = "shipping_calc_{$request->customer_id}_{$request->courier}_{$request->weight}_{$request->origin_city_id}";
        $cachedResult = Cache::get($cacheKey);
        
        if ($cachedResult) {
            return response()->json($cachedResult);
        }

        try {
            $customer = Customer::find($request->customer_id);
            
            if (!$customer->hasLocationData()) {
                $result = [
                    'success' => false,
                    'error' => 'Customer address does not contain location data. Please update customer address using the form below.'
                ];
                Cache::put($cacheKey, $result, now()->addMinutes(10)); // Cache error juga
                return response()->json($result, 400);
            }

            $destinationCityId = $customer->getCityIdFromAddress();
            
            // Validasi cepat sebelum hit API
            if (empty($destinationCityId)) {
                $result = [
                    'success' => false,
                    'error' => 'Invalid destination city ID'
                ];
                Cache::put($cacheKey, $result, now()->addMinutes(10));
                return response()->json($result, 400);
            }

            $shippingCosts = $this->rajaOngkir->getShippingCost(
                $request->origin_city_id,
                $destinationCityId,
                $request->weight,
                $request->courier
            );

            if (empty($shippingCosts)) {
                $result = [
                    'success' => false,
                    'error' => 'No shipping services available for the selected route.'
                ];
                Cache::put($cacheKey, $result, now()->addMinutes(30)); // Cache no results
                return response()->json($result, 404);
            }

            $result = [
                'success' => true,
                'customer' => [
                    'id' => $customer->id_customer,
                    'nama' => $customer->nama,
                    'alamat' => $customer->alamat,
                    'street_address' => $customer->street_address,
                    'city_name' => $customer->getCityNameFromAddress(),
                    'province_name' => $customer->getProvinceNameFromAddress(),
                    'city_id' => $destinationCityId,
                ],
                'shipping_costs' => $shippingCosts,
                'origin_city_id' => $request->origin_city_id,
                'destination_city_id' => $destinationCityId,
                'weight' => $request->weight,
                'courier' => $request->courier,
                'cached' => false
            ];

            // Cache successful result untuk 1 jam
            Cache::put($cacheKey, $result, now()->addHour());

            return response()->json($result);

        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'error' => 'Failed to calculate shipping cost: ' . $e->getMessage()
            ];
            Cache::put($cacheKey, $result, now()->addMinutes(5)); // Cache error pendek
            return response()->json($result, 500);
        }
    }

    /**
     * Update customer address with location data
     */
    public function updateCustomerAddress(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id_customer',
            'street_address' => 'required|string|max:500',
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'province_name' => 'required|string|max:100',
            'city_name' => 'required|string|max:100',
        ]);

        try {
            $customer = Customer::find($request->customer_id);
            
            $formattedAddress = $customer->formatAddressWithLocation(
                $request->street_address,
                $request->city_id,
                $request->city_name,
                $request->province_id,
                $request->province_name
            );

            $customer->update([
                'alamat' => $formattedAddress
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer address updated successfully with location data.',
                'address' => $formattedAddress,
                'street_address' => $request->street_address,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update customer address: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get provinces for dropdown
     */
    public function getProvinces(): JsonResponse
    {
        try {
            $provinces = $this->rajaOngkir->getProvinces();
            return response()->json([
                'success' => true,
                'data' => $provinces
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch provinces'
            ], 500);
        }
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request): JsonResponse
    {
        try {
            $provinceId = $request->get('province_id');
            $cities = $this->rajaOngkir->getCities($provinceId);
            
            return response()->json([
                'success' => true,
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch cities'
            ], 500);
        }
    }

    /**
     * Get available couriers
     */
    public function getCouriers(): JsonResponse
    {
        $couriers = $this->rajaOngkir->getAvailableCouriers();
        return response()->json([
            'success' => true,
            'data' => $couriers
        ]);
    }

    /**
     * Check API status
     */
    public function checkApiStatus(): JsonResponse
    {
        $status = $this->rajaOngkir->checkApiStatus();
        return response()->json([
            'success' => $status,
            'message' => $status ? 'API is working properly' : 'API is not responding'
        ]);
    }
}