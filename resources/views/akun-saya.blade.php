@extends("template")

@section("title", "Akun Saya - Aksesoris Ria")

@section("body")
    <!-- My Account -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="wrap-sidebar-account">
                        <ul class="my-account-nav content-append">
                            <li><span class="my-account-nav-item active">Dashboard</span></li>
                            <li><a href="{{ route("akun.pesanan") }}" class="my-account-nav-item">Pesanan</a></li>
                            <li><a href="{{ route("akun.alamat") }}" class="my-account-nav-item">Alamat</a></li>
                            <li><a href="{{ route("akun.edit") }}" class="my-account-nav-item">Detail Akun</a></li>
                            <li>
                                <form action="{{ route("logout") }}" method="POST">
                                    @csrf
                                    <button type="submit" class="my-account-nav-item"
                                        style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-dashboard">
                        <div class="mb_60">
                            <h3 class="fw-semibold mb-20">Halo {{ Auth::user()->nama ?? "Pengguna" }}</h3>
                            <p>
                                Dari dashboard akun Anda, Anda dapat melihat
                                <a class="text-secondary link fw-medium" href="{{ route("akun.pesanan") }}">
                                    pesanan terbaru
                                </a>
                                , mengelola
                                <a class="text-secondary link fw-medium" href="{{ route("akun.alamat") }}">
                                    alamat pengiriman dan pembayaran
                                </a>
                                , dan
                                <a class="text-secondary link fw-medium" href="{{ route("akun.edit") }}">
                                    mengedit kata sandi dan detail akun Anda
                                </a>.
                            </p>
                        </div>

                        <!-- Account Info Cards -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="icon-user fs-24 text-primary me-3"></i>
                                            <h5 class="mb-0 fw-semibold">Informasi Akun</h5>
                                        </div>
                                        <p class="body-text-3 mb-2"><strong>Nama:</strong> {{ Auth::user()->nama ?? "-" }}
                                        </p>
                                        <p class="body-text-3 mb-2"><strong>Email:</strong> {{ Auth::user()->email ?? "-" }}
                                        </p>
                                        <p class="body-text-3 mb-3"><strong>Telepon:</strong>
                                            {{ Auth::user()->no_hp ?? "-" }}</p>
                                        <a href="{{ route("akun.edit") }}" class="tf-btn btn-gray w-100">
                                            <span class="text-white">Edit Profil</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="icon-location fs-24 text-primary me-3"></i>
                                            <h5 class="mb-0 fw-semibold">Alamat Utama</h5>
                                        </div>
                                        @if (Auth::user()->alamat)
                                            <p class="body-text-3 mb-3">{{ Auth::user()->alamat }}</p>
                                        @else
                                            <p class="body-text-3 mb-3 text-muted">Belum ada alamat yang ditambahkan</p>
                                        @endif
                                        <a href="{{ route("akun.alamat") }}" class="tf-btn btn-gray w-100">
                                            <span class="text-white">Kelola Alamat</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="icon-cart2 fs-24 text-primary me-3"></i>
                                            <h5 class="mb-0 fw-semibold">Pesanan Terbaru</h5>
                                        </div>

                                        @if (isset($recentOrders) && $recentOrders->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>No. Pesanan</th>
                                                            <th>Tanggal</th>
                                                            <th>Status</th>
                                                            <th>Total</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($recentOrders as $order)
                                                            <tr>
                                                                <td>{{ $order->order_number }}</td>
                                                                <td>{{ $order->created_at->format("d M Y") }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-primary">{{ $order->status }}</span>
                                                                </td>
                                                                <td>Rp {{ number_format($order->total, 0, ",", ".") }}</td>
                                                                <td>
                                                                    <a href="{{ route("akun.pesanan.detail", $order->id) }}"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        Lihat
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <a href="{{ route("akun.pesanan") }}" class="tf-btn btn-line w-100 mt-3">
                                                <span>Lihat Semua Pesanan</span>
                                            </a>
                                        @else
                                            <div class="text-center py-4">
                                                <svg width="100" height="100" viewBox="0 0 100 100" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="mb-3">
                                                    <path
                                                        d="M80.6344 72.6641H33.3641C32.8541 72.6646 32.3525 72.5345 31.907 72.2864C31.4615 72.0383 31.0869 71.6803 30.8188 71.2465C30.5507 70.8127 30.398 70.3176 30.3753 69.8081C30.3526 69.2987 30.4606 68.7919 30.6891 68.336L33.4656 62.7844C33.6401 62.4347 33.678 62.0325 33.5719 61.6563L22.0563 21.361C21.7786 20.4019 21.1977 19.5587 20.4005 18.9575C19.6033 18.3564 18.6328 18.0298 17.6344 18.0266H7.78282C7.36822 18.0266 6.97059 18.1913 6.67742 18.4845C6.38425 18.7777 6.21954 19.1753 6.21954 19.5899C6.21954 20.0045 6.38425 20.4021 6.67742 20.6953C6.97059 20.9885 7.36822 21.1532 7.78282 21.1532H17.6359C17.9554 21.1542 18.2658 21.2587 18.5208 21.4511C18.7758 21.6436 18.9615 21.9135 19.05 22.2204L30.3984 61.9313L27.8922 66.9391C27.4257 67.8717 27.2054 68.9081 27.2523 69.9497C27.2991 70.9914 27.6115 72.0038 28.1598 72.8908C28.7081 73.7777 29.4741 74.5098 30.3849 75.0173C31.2958 75.5249 32.3213 75.7911 33.3641 75.7907H80.6344C81.0488 75.7907 81.4462 75.6261 81.7392 75.333C82.0323 75.04 82.1969 74.6426 82.1969 74.2282C82.1969 73.8138 82.0323 73.4163 81.7392 73.1233C81.4462 72.8303 81.0488 72.6641 80.6344 72.6641Z"
                                                        fill="#73787D" />
                                                </svg>
                                                <h6 class="mb-3">Belum Ada Pesanan</h6>
                                                <p class="body-text-3 mb-3">Anda belum memiliki pesanan. Mari mulai
                                                    berbelanja!</p>
                                                <a href="{{ route("home") }}" class="tf-btn btn-gray">
                                                    <span class="text-white">Mulai Belanja</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account -->
@endsection
