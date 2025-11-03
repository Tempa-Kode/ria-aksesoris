@extends("template")

@section("title", "Edit Akun - Aksesoris Ria")

@section("body")
    <!-- My Account Edit -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="wrap-sidebar-account">
                        <ul class="my-account-nav content-append">
                            <li><a href="{{ route("akun.saya") }}" class="my-account-nav-item">Dashboard</a></li>
                            <li><a href="{{ route("akun.pesanan") }}" class="my-account-nav-item">Pesanan</a></li>
                            <li><a href="{{ route("akun.alamat") }}" class="my-account-nav-item">Alamat</a></li>
                            <li><span class="my-account-nav-item active">Detail Akun</span></li>
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
                    <div class="my-account-content account-edit">
                        <h3 class="fw-semibold mb-30">Detail Akun</h3>

                        @if (session("success"))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session("success") }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route("akun.update") }}" method="POST" class="form-edit-account">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Nama Lengkap *</label>
                                        <input type="text" name="nama" value="{{ old("nama", Auth::user()->nama) }}"
                                            required>
                                        @error("nama")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Email *</label>
                                        <input type="email" name="email" value="{{ old("email", Auth::user()->email) }}"
                                            required>
                                        @error("email")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">No. Telepon</label>
                                        <input type="text" name="no_hp"
                                            value="{{ old("no_hp", Auth::user()->no_hp) }}">
                                        @error("no_hp")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Username</label>
                                        <input type="text" name="username" value="{{ Auth::user()->username ?? "-" }}">
                                    </fieldset>
                                </div>
                            </div>

                            <h5 class="fw-semibold mb-20 mt-4">Ubah Password</h5>
                            <p class="body-text-3 mb-3">Kosongkan jika tidak ingin mengubah password</p>

                            <div class="row">
                                <div class="col-12">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Password Saat Ini</label>
                                        <input type="password" name="current_password">
                                        @error("current_password")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Password Baru</label>
                                        <input type="password" name="new_password">
                                        @error("new_password")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Konfirmasi Password Baru</label>
                                        <input type="password" name="new_password_confirmation">
                                    </fieldset>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="tf-btn btn-gray">
                                    <span class="text-white">Simpan Perubahan</span>
                                </button>
                                <a href="{{ route("akun.saya") }}" class="tf-btn btn-line">
                                    <span>Batal</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account Edit -->
@endsection
