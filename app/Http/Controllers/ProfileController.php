<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\BinderbyteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    protected $binderbyteService;

    public function __construct(BinderbyteService $binderbyteService)
    {
        $this->binderbyteService = $binderbyteService;
    }

    /**
     * Show the profile page
     */
    public function show()
    {
        $provinces = $this->binderbyteService->getProvinces();
        return view('frontend.profile', compact('provinces'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id_customer . ',id_customer',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'province_id' => 'nullable|string',
            'city_id' => 'nullable|string',
            'province_name' => 'nullable|string',
            'city_name' => 'nullable|string',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update data profil
        $customer->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'province_name' => $request->province_name,
            'city_name' => $request->city_name,
        ]);

        // Update password jika diisi
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect'])
                    ->withInput();
            }

            $customer->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}