<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::debug('Memulai proses mengambil data admin');
            
            $searchTerm = request()->input('search', '');
            $sortBy = request()->input('sort_by', 'terbaru');
            
            // Base query
            $query = Admin::query();

            // Search by name, email, or phone
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('no_hp', 'like', "%{$searchTerm}%")
                    ->orWhere('alamat', 'like', "%{$searchTerm}%");
                });
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
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'terbaru':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $admin = $query->paginate(10);

            Log::debug('Berhasil mengambil data admin', [
                'jumlah' => $admin->count(),
                'total' => $admin->total(),
                'search_term' => $searchTerm
            ]);
            
            return view('admin.adminn.index', compact('admin', 'searchTerm', 'sortBy'));
            
        } catch (\Exception $e) {
            Log::error('Error pada index admin: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data admin');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.adminn.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        try {
            Admin::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            return redirect()->route('admin.adminn.index')->with('success', 'Admin berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan admin: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.adminn.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.adminn.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id . ',id_admin',
            'password' => 'nullable|min:6|confirmed',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
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

            $admin->update($data);

            return redirect()->route('admin.adminn.index')->with('success', 'Data admin berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui admin: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            
            // Cek jika admin mencoba menghapus dirinya sendiri
            if (auth('admin')->check() && auth('admin')->id() == $id) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri.');
            }

            $admin->delete();

            return redirect()->route('admin.adminn.index')->with('success', 'Admin berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus admin: ' . $e->getMessage());
        }
    }

    /**
     * Search admins
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('search', '');
            $sortBy = $request->input('sort_by', 'terbaru');
            
            Log::debug('Memulai proses search admin', [
                'search_term' => $searchTerm,
                'sort_by' => $sortBy
            ]);

            // Base query
            $query = Admin::query();

            // Search by name, email, or phone
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('no_hp', 'like', "%{$searchTerm}%")
                    ->orWhere('alamat', 'like', "%{$searchTerm}%");
                });
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
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'terbaru':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $admin = $query->paginate(10);

            Log::debug('Search admin berhasil', [
                'jumlah_ditemukan' => $admin->total(),
                'search_term' => $searchTerm
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.adminn.partials.admin-table', compact('admin'))->render(),
                    'pagination' => (string) $admin->links(),
                    'total' => $admin->total()
                ]);
            }

            return view('admin.adminn.index', compact('admin', 'searchTerm', 'sortBy'));

        } catch (\Exception $e) {
            Log::error('Error pada search admin: ' . $e->getMessage(), [
                'search_term' => $searchTerm ?? '',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mencari admin'
                ], 500);
            }

            return redirect()->route('admin.adminn.index')
                ->with('error', 'Terjadi kesalahan saat mencari admin: ' . $e->getMessage());
        }
    }
}