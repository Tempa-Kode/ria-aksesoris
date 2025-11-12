<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

@include("partials.dashboard.head")

<body>

    @include("partials.dashboard.sidebar")

    <main class="dashboard-main">
        @include("partials.dashboard.navbar")

        <div class="dashboard-main-body">

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Edit Data Produk</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route("dashboard.admin") }}"
                            class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">
                        <a href="{{ route("produk.index") }}"
                            class="d-flex align-items-center gap-1 hover-text-primary">
                            Data Produk
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Edit</li>
                </ul>
            </div>

            @if (session("error"))
                <div class="alert alert-danger">
                    {{ session("error") }}
                </div>
            @elseif (session("success"))
                <div class="alert alert-success">
                    {{ session("success") }}
                </div>
            @endif

            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Form Edit Produk</h6>
                </div>
                <div class="card-body p-24">
                    <form action="{{ route("produk.update", $produk->id_produk) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <!-- Informasi Produk -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3 text-primary-600">Informasi Produk</h6>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="nama"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Nama Produk <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control radius-8 @error("nama") is-invalid @enderror" id="nama"
                                        name="nama" value="{{ old("nama", $produk->nama) }}"
                                        placeholder="Masukkan nama produk">
                                    @error("nama")
                                        <div class="text-danger-600 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="kategori_id"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Kategori <span class="text-danger-600">*</span>
                                    </label>
                                    <select class="form-select radius-8 @error("kategori_id") is-invalid @enderror"
                                        id="kategori_id" name="kategori_id">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id_kategori }}"
                                                {{ old("kategori_id", $produk->kategori_id) == $kategori->id_kategori ? "selected" : "" }}>
                                                {{ $kategori->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("kategori_id")
                                        <div class="text-danger-600 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="harga"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Harga <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="number"
                                        class="form-control radius-8 @error("harga") is-invalid @enderror"
                                        id="harga" name="harga" value="{{ old("harga", $produk->harga) }}"
                                        placeholder="Masukkan harga" min="0" step="0.01">
                                    @error("harga")
                                        <div class="text-danger-600 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="jumlah_produk"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Jumlah Stok
                                    </label>
                                    <input type="number"
                                        class="form-control radius-8 @error("jumlah_produk") is-invalid @enderror"
                                        id="jumlah_produk" name="jumlah_produk"
                                        value="{{ old("jumlah_produk", $produk->jumlah_produk) }}"
                                        placeholder="Masukkan jumlah stok" readonly>
                                    @error("jumlah_produk")
                                        <div class="text-danger-600 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-20">
                                    <label for="keterangan"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Keterangan/Deskripsi
                                    </label>
                                    <textarea class="form-control radius-8 @error("keterangan") is-invalid @enderror" id="keterangan" name="keterangan"
                                        rows="3" placeholder="Masukkan keterangan produk">{{ old("keterangan", $produk->keterangan) }}</textarea>
                                    @error("keterangan")
                                        <div class="text-danger-600 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Gambar Produk -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3 text-primary-600">Gambar Produk (Maksimal 3 Gambar)</h6>
                            </div>

                            @for ($i = 1; $i <= 3; $i++)
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label for="gambar_{{ $i }}"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Gambar {{ $i }}
                                        </label>

                                        @if ($produk->{"gambar_{$i}"})
                                            <div class="mb-2 position-relative">
                                                <img src="{{ asset($produk->{"gambar_{$i}"}) }}" class="img-thumbnail"
                                                    style="max-width: 200px; max-height: 200px;"
                                                    id="current_gambar_{{ $i }}">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="hapus_gambar_{{ $i }}" value="1"
                                                        id="hapus_gambar_{{ $i }}"
                                                        onchange="toggleImagePreview({{ $i }})">
                                                    <label class="form-check-label text-danger"
                                                        for="hapus_gambar_{{ $i }}">
                                                        Hapus gambar ini
                                                    </label>
                                                </div>
                                            </div>
                                        @endif

                                        <input type="file"
                                            class="form-control radius-8 @error("gambar_{$i}") is-invalid @enderror"
                                            id="gambar_{{ $i }}" name="gambar_{{ $i }}"
                                            accept="image/*"
                                            onchange="previewImage(this, 'preview_gambar_{{ $i }}')">
                                        @error("gambar_{$i}")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                        <div id="preview_gambar_{{ $i }}" class="mt-2"></div>
                                    </div>
                                </div>
                            @endfor

                            <div class="col-12">
                                <small class="text-secondary-light">Format: JPG, JPEG, PNG. Max: 2MB per file</small>
                            </div>
                        </div>

                        <!-- Jenis Produk Existing -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3 text-primary-600">Jenis/Variasi Produk Saat Ini</h6>
                            </div>
                            <div class="col-12">
                                @foreach ($produk->jenisProduk as $jenis)
                                    <div class="card mb-3 jenis-existing-item"
                                        data-id="{{ $jenis->id_jenis_produk }}">
                                        <div class="card-body p-20">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-semibold mb-0">Edit Jenis Produk</h6>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger radius-8 hapus-jenis-existing"
                                                    data-id="{{ $jenis->id_jenis_produk }}">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon> Hapus
                                                </button>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="jenis_existing_id[]"
                                                    value="{{ $jenis->id_jenis_produk }}">
                                                <div class="col-sm-4">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                            Jenis</label>
                                                        <input type="text" class="form-control radius-8"
                                                            name="jenis_existing_nama[]" value="{{ $jenis->nama }}"
                                                            placeholder="Contoh: Warna Merah">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah
                                                            Stok</label>
                                                        <input type="number" class="form-control radius-8"
                                                            name="jenis_existing_jumlah[]"
                                                            value="{{ $jenis->jumlah_produk }}" placeholder="0"
                                                            min="0" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar
                                                            Baru (Opsional)</label>
                                                        <input type="file"
                                                            class="form-control radius-8 jenis-existing-gambar"
                                                            name="jenis_existing_gambar_{{ $jenis->id_jenis_produk }}"
                                                            accept="image/*">
                                                        <small class="text-secondary-light">Kosongkan jika tidak ingin
                                                            mengubah gambar</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    @if ($jenis->path_gambar)
                                                        <div class="mb-2">
                                                            <label
                                                                class="form-label fw-semibold text-primary-light text-sm mb-2">Gambar
                                                                Saat Ini:</label>
                                                            <img src="{{ asset($jenis->path_gambar) }}"
                                                                class="w-25 h-100-px rounded border d-block">
                                                        </div>
                                                    @endif
                                                    <div class="preview-existing-{{ $jenis->id_jenis_produk }}"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tambah Jenis Baru -->
                        <div class="row mb-4">
                            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0 text-primary-600">Tambah Jenis/Variasi Baru</h6>
                                <button type="button" class="btn btn-sm btn-primary radius-8" id="tambah-jenis">
                                    <iconify-icon icon="ic:baseline-plus"></iconify-icon> Tambah Jenis
                                </button>
                            </div>
                            <div class="col-12">
                                <div id="jenis-produk-container"></div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <a href="{{ route("produk.index") }}"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                Batal
                            </a>
                            <button type="submit"
                                class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        @include("partials.dashboard.footer")
    </main>
    @include("partials.dashboard.scripts")

    <script>
        // Preview Gambar (untuk 3 gambar produk)
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <label class="form-label fw-semibold text-primary-light text-sm mb-2">Preview:</label>
                        <img src="${e.target.result}" class="img-thumbnail d-block" style="max-width: 200px; max-height: 200px;">
                    `;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Toggle tampilan gambar saat checkbox hapus dicentang
        function toggleImagePreview(index) {
            const checkbox = document.getElementById('hapus_gambar_' + index);
            const currentImage = document.getElementById('current_gambar_' + index);

            if (checkbox.checked) {
                currentImage.style.opacity = '0.3';
                currentImage.style.filter = 'grayscale(100%)';
            } else {
                currentImage.style.opacity = '1';
                currentImage.style.filter = 'none';
            }
        }

        $(document).ready(function() {
            let jenisIndex = 0;

            // Hapus Jenis Existing
            $('.hapus-jenis-existing').on('click', function() {
                const id = $(this).data('id');
                const item = $(this).closest('.jenis-existing-item');

                Swal.fire({
                    title: 'Hapus jenis produk?',
                    text: "Jenis produk akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/produk/jenis/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                item.remove();
                                Swal.fire('Terhapus!', 'Jenis produk berhasil dihapus.',
                                    'success');
                            }
                        });
                    }
                });
            });

            // Tambah Jenis Baru
            $('#tambah-jenis').on('click', function() {
                const html = `
                    <div class="card mb-3 jenis-item" data-index="${jenisIndex}">
                        <div class="card-body p-20">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Jenis Produk Baru #${jenisIndex + 1}</h6>
                                <button type="button" class="btn btn-sm btn-danger radius-8 hapus-jenis">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon> Hapus
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Jenis</label>
                                        <input type="text" class="form-control radius-8" name="jenis_nama[]" placeholder="Contoh: Warna Biru">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah Stok</label>
                                        <input type="number" class="form-control radius-8" name="jenis_jumlah[]" placeholder="0" min="0">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar</label>
                                        <input type="file" class="form-control radius-8 jenis-gambar" name="jenis_gambar[]" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="preview-jenis-${jenisIndex}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#jenis-produk-container').append(html);
                jenisIndex++;
            });

            // Hapus Jenis Baru
            $(document).on('click', '.hapus-jenis', function() {
                $(this).closest('.jenis-item').remove();
            });

            // Preview Gambar Jenis Baru
            $(document).on('change', '.jenis-gambar', function(e) {
                const index = $(this).closest('.jenis-item').data('index');
                const previewContainer = $(`.preview-jenis-${index}`);
                previewContainer.html('');

                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.html(`
                            <img src="${e.target.result}" class="w-25 h-100-px rounded border mt-2">
                        `);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Preview Gambar Jenis Existing yang Diubah
            $(document).on('change', '.jenis-existing-gambar', function(e) {
                const jenisId = $(this).attr('name').split('_').pop();
                const previewContainer = $(`.preview-existing-${jenisId}`);
                previewContainer.html('');

                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.html(`
                            <label class="form-label fw-semibold text-primary-light text-sm mb-2 mt-2">Preview Gambar Baru:</label>
                            <img src="${e.target.result}" class="w-25 h-100-px rounded border d-block">
                        `);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });
    </script>
</body>

</html>
