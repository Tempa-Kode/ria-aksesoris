<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'itemTransaksi.produk']);

        // Filter by status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        // Filter by status pengiriman
        if ($request->filled('status_pengiriman')) {
            $query->where('status_pengiriman', $request->status_pengiriman);
        }

        // Search by kode invoice or nama customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_invoice', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->latest()->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Invoice::with(['customer', 'itemTransaksi.produk.gambarProduk'])
            ->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Display invoice
     */
    public function invoice(string $id)
    {
        $transaksi = Invoice::with(['customer', 'itemTransaksi.produk'])
            ->findOrFail($id);
        return view('transaksi.invoice', compact('transaksi'));
    }

    /**
     * Validate payment
     */
    public function validatePayment(Request $request, string $id)
    {
        $transaksi = Invoice::findOrFail($id);

        $validated = $request->validate([
            'status_pembayaran' => 'required|in:pending,terima,tolak',
        ]);

        $transaksi->update([
            'status_pembayaran' => $validated['status_pembayaran']
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diupdate!');
    }

    /**
     * Update shipping info
     */
    public function updateShipping(Request $request, string $id)
    {
        $transaksi = Invoice::findOrFail($id);

        $validated = $request->validate([
            'resi' => 'required|string|max:255',
            'status_pengiriman' => 'required|boolean',
        ]);

        $transaksi->update($validated);

        return redirect()->back()->with('success', 'Informasi pengiriman berhasil diupdate!');
    }
}
