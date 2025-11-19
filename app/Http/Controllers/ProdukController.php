<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\JenisProduk;
use App\Models\RiwayatStokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::with(['kategori', 'jenisProduk'])
            ->latest()
            ->get();
        return view('produk.index', compact('produks'));
    }

    public function pdf()
    {
        $produks = Produk::with(['kategori', 'jenisProduk'])->get();
        $pdf = Pdf::loadView('produk.pdf', compact('produks'));
        return $pdf->stream('laporan-produk.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id_kategori',
            'nama' => 'required|string|max:255',
            'harga_modal' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'jumlah_produk' => 'nullable|integer|min:0',
            'gambar_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_nama.*' => 'nullable|string|max:255',
            'jenis_gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_jumlah.*' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Prepare produk data
            $produkData = [
                'kategori_id' => $validated['kategori_id'],
                'nama' => $validated['nama'],
                'harga_modal' => $validated['harga_modal'],
                'harga' => $validated['harga'],
                'keterangan' => $validated['keterangan'] ?? null,
                'jumlah_produk' => $validated['jumlah_produk'] ?? null,
            ];

            // Upload Gambar Produk (maksimal 3)
            for ($i = 1; $i <= 3; $i++) {
                if ($request->hasFile('gambar_' . $i)) {
                    $gambar = $request->file('gambar_' . $i);
                    $filename = time() . '_' . $i . '_' . uniqid() . '.' . $gambar->getClientOriginalExtension();
                    $gambar->move(public_path('uploads/produk'), $filename);
                    $produkData['gambar_' . $i] = 'uploads/produk/' . $filename;
                }
            }

            // Create Produk
            $produk = Produk::create($produkData);

            // Create Jenis Produk
            if ($request->has('jenis_nama')) {
                foreach ($request->jenis_nama as $index => $jenisNama) {
                    if (!empty($jenisNama)) {
                        $jenisData = [
                            'produk_id' => $produk->id_produk,
                            'nama' => $jenisNama,
                            'jumlah_produk' => $request->jenis_jumlah[$index] ?? 0,
                        ];

                        // Upload Gambar Jenis Produk
                        if ($request->hasFile('jenis_gambar.' . $index)) {
                            $jenisGambar = $request->file('jenis_gambar')[$index];
                            $filename = time() . '_' . uniqid() . '.' . $jenisGambar->getClientOriginalExtension();
                            $jenisGambar->move(public_path('uploads/jenis-produk'), $filename);
                            $jenisData['path_gambar'] = 'uploads/jenis-produk/' . $filename;
                        }

                        $jenisProduk = JenisProduk::create($jenisData);

                        // Create Riwayat Stok untuk Jenis Produk
                        RiwayatStokProduk::create([
                            'produk_id' => $produk->id_produk,
                            'jenis_produk_id' => $jenisProduk->id_jenis_produk,
                            'tanggal' => now(),
                            'stok_awal' => 0,
                            'produk_masuk' => $jenisData['jumlah_produk'],
                            'produk_keluar' => 0,
                            'stok_akhir' => $jenisData['jumlah_produk'],
                        ]);
                    }
                }
            }

            // Create Riwayat Stok Produk Utama (hanya jika jumlah_produk > 0)
            if ($validated['jumlah_produk'] > 0) {
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => null,
                    'tanggal' => now(),
                    'stok_awal' => 0,
                    'produk_masuk' => $validated['jumlah_produk'],
                    'produk_keluar' => 0,
                    'stok_akhir' => $validated['jumlah_produk'],
                ]);
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Data produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with(['kategori', 'jenisProduk', 'riwayatStokProduk'])
            ->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::with(['jenisProduk'])->findOrFail($id);
        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id_kategori',
            'nama' => 'required|string|max:255',
            'harga_modal' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'jumlah_produk' => 'nullable|integer|min:0',
            'gambar_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'hapus_gambar_1' => 'nullable|boolean',
            'hapus_gambar_2' => 'nullable|boolean',
            'hapus_gambar_3' => 'nullable|boolean',
            // Jenis Existing
            'jenis_existing_id.*' => 'nullable|exists:jenis_produk,id_jenis_produk',
            'jenis_existing_nama.*' => 'nullable|string|max:255',
            'jenis_existing_jumlah.*' => 'nullable|integer|min:0',
            // Jenis Baru
            'jenis_nama.*' => 'nullable|string|max:255',
            'jenis_gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_jumlah.*' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $stokLama = $produk->jumlah_produk;

            // Prepare update data
            $updateData = [
                'kategori_id' => $validated['kategori_id'],
                'nama' => $validated['nama'],
                'harga_modal' => $validated['harga_modal'],
                'harga' => $validated['harga'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            // Handle gambar produk (3 gambar)
            for ($i = 1; $i <= 3; $i++) {
                // Hapus gambar jika diminta
                if ($request->input("hapus_gambar_{$i}")) {
                    if ($produk->{"gambar_{$i}"} && file_exists(public_path($produk->{"gambar_{$i}"}))) {
                        unlink(public_path($produk->{"gambar_{$i}"}));
                    }
                    $updateData["gambar_{$i}"] = null;
                }

                // Upload gambar baru jika ada
                if ($request->hasFile("gambar_{$i}")) {
                    // Hapus gambar lama jika ada
                    if ($produk->{"gambar_{$i}"} && file_exists(public_path($produk->{"gambar_{$i}"}))) {
                        unlink(public_path($produk->{"gambar_{$i}"}));
                    }

                    $gambar = $request->file("gambar_{$i}");
                    $filename = time() . "_{$i}_" . uniqid() . '.' . $gambar->getClientOriginalExtension();
                    $gambar->move(public_path('uploads/produk'), $filename);
                    $updateData["gambar_{$i}"] = 'uploads/produk/' . $filename;
                }
            }

            // Update Produk
            $produk->update($updateData);

            // Update Jenis Produk Existing
            if ($request->has('jenis_existing_id')) {
                foreach ($request->jenis_existing_id as $index => $jenisId) {
                    $jenis = JenisProduk::find($jenisId);
                    if ($jenis) {
                        $stokLamaJenis = $jenis->jumlah_produk;
                        $stokBaruJenis = $request->jenis_existing_jumlah[$index] ?? 0;

                        $updateData = [
                            'nama' => $request->jenis_existing_nama[$index],
                            'jumlah_produk' => $stokBaruJenis,
                        ];

                        // Upload Gambar Baru untuk Jenis Existing (jika ada)
                        if ($request->hasFile("jenis_existing_gambar_{$jenisId}")) {
                            $jenisGambar = $request->file("jenis_existing_gambar_{$jenisId}");

                            // Hapus gambar lama jika ada
                            if ($jenis->path_gambar && file_exists(public_path($jenis->path_gambar))) {
                                unlink(public_path($jenis->path_gambar));
                            }

                            // Upload gambar baru
                            $filename = time() . '_' . uniqid() . '.' . $jenisGambar->getClientOriginalExtension();
                            $jenisGambar->move(public_path('uploads/jenis-produk'), $filename);
                            $updateData['path_gambar'] = 'uploads/jenis-produk/' . $filename;
                        }

                        $jenis->update($updateData);

                        // Create Riwayat Stok jika ada perubahan stok jenis
                        if ($stokLamaJenis != $stokBaruJenis) {
                            $selisih = $stokBaruJenis - $stokLamaJenis;
                            RiwayatStokProduk::create([
                                'produk_id' => $produk->id_produk,
                                'jenis_produk_id' => $jenisId,
                                'tanggal' => now(),
                                'stok_awal' => $stokLamaJenis,
                                'produk_masuk' => $selisih > 0 ? $selisih : 0,
                                'produk_keluar' => $selisih < 0 ? abs($selisih) : 0,
                                'stok_akhir' => $stokBaruJenis,
                            ]);
                        }
                    }
                }
            }

            // Tambah Jenis Produk Baru
            if ($request->has('jenis_nama')) {
                foreach ($request->jenis_nama as $index => $jenisNama) {
                    if (!empty($jenisNama)) {
                        $jenisData = [
                            'nama' => $jenisNama,
                            'jumlah_produk' => $request->jenis_jumlah[$index] ?? 0,
                        ];

                        // Upload Gambar Jenis Produk
                        if ($request->hasFile('jenis_gambar.' . $index)) {
                            $jenisGambar = $request->file('jenis_gambar')[$index];
                            $filename = time() . '_' . uniqid() . '.' . $jenisGambar->getClientOriginalExtension();
                            $jenisGambar->move(public_path('uploads/jenis-produk'), $filename);
                            $jenisData['path_gambar'] = 'uploads/jenis-produk/' . $filename;
                        }

                        $jenisProduk = JenisProduk::create([
                            'produk_id' => $produk->id_produk,
                            ...$jenisData
                        ]);

                        // Create Riwayat Stok untuk Jenis Produk Baru (jika ada stok)
                        if ($jenisData['jumlah_produk'] > 0) {
                            RiwayatStokProduk::create([
                                'produk_id' => $produk->id_produk,
                                'jenis_produk_id' => $jenisProduk->id_jenis_produk,
                                'tanggal' => now(),
                                'stok_awal' => 0,
                                'produk_masuk' => $jenisData['jumlah_produk'],
                                'produk_keluar' => 0,
                                'stok_akhir' => $jenisData['jumlah_produk'],
                            ]);
                        }
                    }
                }
            }

            // Create Riwayat Stok jika ada perubahan dan jumlah_produk dikirim
            if (isset($validated['jumlah_produk']) && $stokLama != $validated['jumlah_produk']) {
                $selisih = $validated['jumlah_produk'] - $stokLama;
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => null,
                    'tanggal' => now(),
                    'stok_awal' => $stokLama,
                    'produk_masuk' => $selisih > 0 ? $selisih : 0,
                    'produk_keluar' => $selisih < 0 ? abs($selisih) : 0,
                    'stok_akhir' => $validated['jumlah_produk'],
                ]);

                // Update jumlah produk utama
                $produk->update(['jumlah_produk' => $validated['jumlah_produk']]);
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Data produk berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete Gambar Produk (3 gambar)
            for ($i = 1; $i <= 3; $i++) {
                if ($produk->{"gambar_{$i}"} && file_exists(public_path($produk->{"gambar_{$i}"}))) {
                    unlink(public_path($produk->{"gambar_{$i}"}));
                }
            }

            // Delete Gambar Jenis Produk
            foreach ($produk->jenisProduk as $jenis) {
                if ($jenis->path_gambar && file_exists(public_path($jenis->path_gambar))) {
                    unlink(public_path($jenis->path_gambar));
                }
                $jenis->delete();
            }

            // Delete Riwayat Stok
            $produk->riwayatStokProduk()->delete();

            // Delete Produk
            $produk->delete();

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Data produk berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Delete jenis produk
     */
    public function deleteJenis($id)
    {
        $jenis = JenisProduk::findOrFail($id);

        if ($jenis->path_gambar && file_exists(public_path($jenis->path_gambar))) {
            unlink(public_path($jenis->path_gambar));
        }

        $jenis->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Tambah stok produk
     */
    public function tambahStok(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'tipe_stok' => 'required|in:masuk,keluar',
            'jenis_produk_id' => 'nullable|exists:jenis_produk,id_jenis_produk',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $tipe = $validated['tipe_stok'];
            $jumlah = $validated['jumlah'];
            $jenisId = $validated['jenis_produk_id'] ?? null;

            // Jika ada jenis produk dipilih
            if ($jenisId) {
                $jenis = JenisProduk::findOrFail($jenisId);
                $stokAwal = $jenis->jumlah_produk;

                // Validasi stok keluar
                if ($tipe === 'keluar' && $stokAwal < $jumlah) {
                    return redirect()->back()->with('error_stok', 'Stok tidak mencukupi! Stok saat ini: ' . $stokAwal);
                }

                // Update stok jenis produk
                $stokAkhir = $tipe === 'masuk'
                    ? $stokAwal + $jumlah
                    : $stokAwal - $jumlah;

                $jenis->update(['jumlah_produk' => $stokAkhir]);

                // Catat riwayat stok
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => $jenisId,
                    'tanggal' => now(),
                    'stok_awal' => $stokAwal,
                    'produk_masuk' => $tipe === 'masuk' ? $jumlah : 0,
                    'produk_keluar' => $tipe === 'keluar' ? $jumlah : 0,
                    'stok_akhir' => $stokAkhir,
                ]);
            } else {
                // Update stok produk utama
                $stokAwal = $produk->jumlah_produk ?? 0;

                // Validasi stok keluar
                if ($tipe === 'keluar' && $stokAwal < $jumlah) {
                    return redirect()->back()->with('error_stok', 'Stok tidak mencukupi! Stok saat ini: ' . $stokAwal);
                }

                $stokAkhir = $tipe === 'masuk'
                    ? $stokAwal + $jumlah
                    : $stokAwal - $jumlah;

                $produk->update(['jumlah_produk' => $stokAkhir]);

                // Catat riwayat stok
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => null,
                    'tanggal' => now(),
                    'stok_awal' => $stokAwal,
                    'produk_masuk' => $tipe === 'masuk' ? $jumlah : 0,
                    'produk_keluar' => $tipe === 'keluar' ? $jumlah : 0,
                    'stok_akhir' => $stokAkhir,
                ]);
            }

            DB::commit();

            $message = $tipe === 'masuk'
                ? "Berhasil menambahkan {$jumlah} stok!"
                : "Berhasil mengurangi {$jumlah} stok!";

            return redirect()->back()->with('success_stok', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_stok', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print riwayat stok produk
     */
    public function printRiwayatStok(string $id)
    {
        $produk = Produk::with(['kategori', 'jenisProduk', 'riwayatStokProduk.jenisProduk'])
            ->findOrFail($id);

        // Group riwayat stok by date and jenis
        $riwayatGrouped = $produk->riwayatStokProduk
            ->sortBy('created_at')
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d') . '_' . ($item->jenis_produk_id ?? 'utama');
            })
            ->map(function ($group) {
                $firstItem = $group->sortBy('created_at')->first();
                return [
                    'tanggal' => $firstItem->created_at,
                    'jenis' => $firstItem->jenisProduk ? $firstItem->jenisProduk->nama : 'Produk Utama',
                    'stok_awal' => $firstItem->stok_awal,
                    'produk_masuk' => $group->sum('produk_masuk'),
                    'produk_keluar' => $group->sum('produk_keluar'),
                    'stok_akhir' => $group->last()->stok_akhir,
                ];
            })
            ->values();

        // Calculate total stok
        $totalStok = $produk->jumlah_produk + $produk->jenisProduk->sum('jumlah_produk');

        return view('produk.print-riwayat', compact('produk', 'riwayatGrouped', 'totalStok'));
    }
}
