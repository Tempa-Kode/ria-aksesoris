<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::latest()->get();

        // Query builder untuk produk
        $query = Produk::with(['kategori', 'jenisProduk', 'gambarProduk']);

        // Search/Pencarian
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('keterangan', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('kategori', function($q) use ($searchTerm) {
                      $q->where('nama', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('jenisProduk', function($q) use ($searchTerm) {
                      $q->where('nama', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        // Sorting
        $sortBy = $request->get('sort', 'best-selling');
        switch ($sortBy) {
            case 'a-z':
                $query->orderBy('nama', 'asc');
                break;
            case 'z-a':
                $query->orderBy('nama', 'desc');
                break;
            case 'price-low-high':
                $query->orderBy('harga', 'asc');
                break;
            case 'price-high-low':
                $query->orderBy('harga', 'desc');
                break;
            case 'best-selling':
            default:
                $query->latest();
                break;
        }

        // Pagination dengan jumlah yang bisa dipilih
        $perPage = $request->get('show', 50);
        $produk = $query->paginate($perPage)->appends($request->except('page'));

        return view('index', compact('kategori', 'produk'));
    }

    public function dashboard()
    {
        if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'karyawan') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Statistik Dashboard
        $totalProduk = \App\Models\Produk::count();
        $totalCustomer = \App\Models\User::where('role', 'customer')->count();
        $totalInvoice = \App\Models\Invoice::count();
        $totalPengguna = \App\Models\User::whereIn('role', ['admin', 'karyawan'])->count();

        // Transaksi Pending
        $invoicePending = \App\Models\Invoice::where('status_pembayaran', 'pending')->count();

        // Transaksi Bulan Ini
        $transaksisBulanIni = \App\Models\Invoice::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->where('status_pembayaran', 'terima')
            ->count();

        // Pendapatan Bulan Ini
        $pendapatanBulanIni = \App\Models\Invoice::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->where('status_pembayaran', 'terima')
            ->sum('total_bayar');

        // Produk Stok Rendah (kurang dari 10)
        $produkStokRendah = \App\Models\Produk::where('jumlah_produk', '<', 10)->count();

        return view('dashboard', compact(
            'totalProduk',
            'totalCustomer',
            'totalInvoice',
            'totalPengguna',
            'invoicePending',
            'transaksisBulanIni',
            'pendapatanBulanIni',
            'produkStokRendah'
        ));
    }
}
