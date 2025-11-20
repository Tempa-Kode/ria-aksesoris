<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Menampilkan seluruh data admin
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        return view('admin.index', compact('admins'));
    }

    /**
     * Menampilkan form untuk menambahkan data admin baru
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Menyimpan data admin baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['role'] = 'admin';
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/admin'), $filename);
            $validated['foto'] = 'uploads/admin/' . $filename;
        }

        User::create($validated);

        return redirect()->route('admin.index')->with('success', 'Data admin berhasil ditambahkan!');
    }

    /**
     * Menampilkan data admin berdasarkan ID
     */
    public function show(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.show', compact('admin'));
    }

    /**
     * Menampilkan form untuk mengedit data admin
     */
    public function edit(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.edit', compact('admin'));
    }

    /**
     * Memperbarui data admin di database
     */
    public function update(Request $request, string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',id_user',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id . ',id_user',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('foto')) {
            // menghapus foto lama jika ada
            if ($admin->foto && file_exists(public_path($admin->foto))) {
                unlink(public_path($admin->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/admin'), $filename);
            $validated['foto'] = 'uploads/admin/' . $filename;
        }

        $admin->update($validated);

        return redirect()->route('admin.index')->with('success', 'Data admin berhasil diupdate!');
    }

    /**
     * Menghapus data admin dari database
     */
    public function destroy(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        // menghapus foto lama jika ada
        if ($admin->foto && file_exists(public_path($admin->foto))) {
            unlink(public_path($admin->foto));
        }

        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Data admin berhasil dihapus!');
    }

    public function downloadStokRendahPdf()
    {
        // 1. Ambil stok dari produk utama yang <= 5
        $produkUtama = Produk::where('jumlah_produk', '<=', 5)
            ->get()
            ->map(function($produk) {
                return [
                    'nama_produk' => $produk->nama,
                    'nama_jenis' => 'Produk Utama', // Produk utama tidak punya jenis
                    'stok' => $produk->jumlah_produk
                ];
            });

        // 2. Ambil stok dari jenis produk yang <= 5
        $jenisProdukRendah = Produk::with(['jenisProduk' => function($query) {
            $query->where('jumlah_produk', '<=', 5)
                ->orderBy('jumlah_produk', 'asc');
        }])
            ->get()
            ->flatMap(function($produk) {
                return $produk->jenisProduk->map(function($jenis) use ($produk) {
                    return [
                        'nama_produk' => $produk->nama,
                        'nama_jenis' => $jenis->nama,
                        'stok' => $jenis->jumlah_produk
                    ];
                });
            });

        // 3. Gabungkan dan sort berdasarkan stok
        $stokProduk = $produkUtama->concat($jenisProdukRendah)
            ->sortBy('stok')
            ->values();

        $pdf = Pdf::loadView('laporan.stok-rendah-pdf', [
            'stokProduk' => $stokProduk,
            'tanggal' => date('d-m-Y')
        ]);

        return $pdf->stream('laporan-stok-rendah-' . date('Y-m-d') . '.pdf');
    }
}

