<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::debug('Memulai proses mengambil data customer');
            
            $customer = Customer::orderBy('created_at', 'desc')->paginate(10);

            // Get unique provinces for filter dropdown
            $provinsiList = Customer::select('province_name')
                ->whereNotNull('province_name')
                ->where('province_name', '!=', '')
                ->distinct()
                ->pluck('province_name')
                ->filter()
                ->values();

            Log::debug('Berhasil mengambil data customer', [
                'jumlah' => $customer->count(),
                'total' => $customer->total()
            ]);
            
            return view('admin.customer.index', compact('customer', 'provinsiList'));
            
        } catch (\Exception $e) {
            Log::error('Error pada index customer: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data customer');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6|confirmed',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        try {
            Customer::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            return redirect()->route('admin.customer.index')->with('success', 'Customer berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan customer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::with(['pemesanan', 'cart'])->findOrFail($id);
        return view('admin.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id . ',id_customer',
            'password' => 'nullable|min:6|confirmed',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        try {
            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ];

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $customer->update($data);

            return redirect()->route('admin.customer.index')->with('success', 'Data customer berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui customer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            
            // Cek jika customer memiliki data terkait
            if ($customer->pemesanan()->count() > 0) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus customer karena memiliki data pemesanan.');
            }

            if ($customer->cart()->count() > 0) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus customer karena memiliki data cart.');
            }

            $customer->delete();

            return redirect()->route('admin.customer.index')->with('success', 'Customer berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus customer: ' . $e->getMessage());
        }
    }

    /**
     * Search customers
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('search', '');
            $provinsi = $request->input('provinsi', '');
            $sortBy = $request->input('sort_by', 'terbaru');
            
            Log::debug('Memulai proses search customer', [
                'search_term' => $searchTerm,
                'provinsi' => $provinsi,
                'sort_by' => $sortBy
            ]);

            // Base query
            $query = Customer::query();

            // Search by name, email, phone, or address
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('no_hp', 'like', "%{$searchTerm}%")
                    ->orWhere('alamat', 'like', "%{$searchTerm}%")
                    ->orWhere('city_name', 'like', "%{$searchTerm}%")
                    ->orWhere('province_name', 'like', "%{$searchTerm}%");
                });
            }

            // Filter by province
            if (!empty($provinsi) && $provinsi !== 'semua') {
                $query->where('province_name', $provinsi);
            }

            // Sorting
            switch ($sortBy) {
                case 'nama_asc':
                    $query->orderBy('nama', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('nama', 'desc');
                    break;
                case 'email_asc':
                    $query->orderBy('email', 'asc');
                    break;
                case 'email_desc':
                    $query->orderBy('email', 'desc');
                    break;
                case 'kota_asc':
                    $query->orderBy('city_name', 'asc');
                    break;
                case 'kota_desc':
                    $query->orderBy('city_name', 'desc');
                    break;
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'terbaru':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $customer = $query->paginate(10);

            // Get unique provinces for filter dropdown
            $provinsiList = Customer::select('province_name')
                ->whereNotNull('province_name')
                ->where('province_name', '!=', '')
                ->distinct()
                ->pluck('province_name')
                ->filter()
                ->values();

            Log::debug('Search customer berhasil', [
                'jumlah_ditemukan' => $customer->total(),
                'search_term' => $searchTerm
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.customer.partials.customer-table', compact('customer'))->render(),
                    'pagination' => (string) $customer->links(),
                    'total' => $customer->total()
                ]);
            }

            return view('admin.customer.index', compact('customer', 'searchTerm', 'provinsi', 'sortBy', 'provinsiList'));

        } catch (\Exception $e) {
            Log::error('Error pada search customer: ' . $e->getMessage(), [
                'search_term' => $searchTerm ?? '',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mencari customer'
                ], 500);
            }

            return redirect()->route('admin.customer.index')
                ->with('error', 'Terjadi kesalahan saat mencari customer: ' . $e->getMessage());
        }
    }
}