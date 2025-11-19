<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produk</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        h1, h3 {
            text-align: center;
        }
        hr {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <h1>Laporan Data Produk</h1>
    <h3>Ria Aksesoris</h3>
    <hr>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Total Stok</th>
                <th>Jenis / Varian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produks as $produk)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama }}</td>
                    <td>{{ $produk->kategori->nama ?? '-' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                    @php
                        $totalStok = $produk->jumlah_produk ?? 0;
                        $totalStok += $produk->jenisProduk->sum('jumlah_produk');
                    @endphp
                    <td class="text-center">{{ $totalStok }}</td>
                    <td>
                        @if($produk->jenisProduk->count() > 0)
                            <ul>
                                @foreach($produk->jenisProduk as $jenis)
                                    <li>{{ $jenis->nama }} (Stok: {{ $jenis->jumlah_produk ?? 0 }})</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
