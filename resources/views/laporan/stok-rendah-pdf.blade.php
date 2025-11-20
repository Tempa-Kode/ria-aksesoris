<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Produk Rendah</title>
    <style>
        body {
            font-family: 'sans-serif';
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #222;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .content-table th,
        .content-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .content-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }
        .content-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-danger {
            color: #d9534f;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Stok Produk Rendah</h1>
            <p>Tanggal Cetak: {{ $tanggal }}</p>
        </div>

        <p>Berikut adalah daftar produk dengan jumlah stok 5 unit atau kurang.</p>

        <table class="content-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Nama Produk</th>
                    <th>Jenis Produk</th>
                    <th class="text-center" style="width: 100px;">Stok Tersisa</th>
                </tr>
            </thead>
            <tbody>
                @if ($stokProduk->count() > 0)
                    @foreach ($stokProduk as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item["nama_produk"] }}</td>
                            <td>{{ $item["nama_jenis"] }}</td>
                            <td class="text-center text-danger">{{ $item["stok"] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada produk dengan stok rendah saat ini.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="footer">
            Laporan ini dibuat oleh sistem secara otomatis.
        </div>
    </div>
</body>
</html>
