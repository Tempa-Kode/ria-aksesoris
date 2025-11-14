@extends("template")
@section("title", $produk->nama . " - Aksesoris Ria")

@section("body")
    <!-- Breakcrumbs -->
    <div class="tf-sp-1">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route("home") }}" class="body-small link">
                        Home
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon-arrow-right"></i>
                </li>
                <li>
                    <a href="{{ route("home", ["kategori" => $produk->kategori_id]) }}" class="body-small link">
                        {{ $produk->kategori->nama }}
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">{{ $produk->nama }}</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Product Main -->
    <section>
        <div class="tf-main-product section-image-zoom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Product Image -->
                        <div class="tf-product-media-wrap bg-white thumbs-default sticky-top">
                            <div class="thumbs-slider">
                                <div class="swiper tf-product-media-main" id="gallery-swiper-started">
                                    <div class="swiper-wrapper">
                                        @php
                                            $hasProductImage = false;
                                        @endphp
                                        @for ($i = 1; $i <= 3; $i++)
                                            @if ($produk->{"gambar_{$i}"})
                                                @php $hasProductImage = true; @endphp
                                                <div class="swiper-slide" data-color="gray">
                                                    <a href="{{ asset($produk->{"gambar_{$i}"}) }}" target="_blank"
                                                        class="item" data-pswp-width="600px" data-pswp-height="800px">
                                                        <img class="tf-image-zoom lazyload"
                                                            src="{{ asset($produk->{"gambar_{$i}"}) }}"
                                                            data-zoom="{{ asset($produk->{"gambar_{$i}"}) }}"
                                                            data-src="{{ asset($produk->{"gambar_{$i}"}) }}" alt="">
                                                    </a>
                                                </div>
                                            @endif
                                        @endfor

                                        @if (!$hasProductImage)
                                            <div class="swiper-slide">
                                                <p>No images available</p>
                                            </div>
                                        @endif

                                        @foreach ($produk->jenisProduk as $jenis)
                                            <div class="swiper-slide" data-color="gray"
                                                data-jenis-id="{{ $jenis->id_jenis_produk }}">
                                                <a href="{{ asset($jenis->path_gambar) }}" target="_blank" class="item"
                                                    data-pswp-width="600px" data-pswp-height="800px">
                                                    <img class="tf-image-zoom lazyload"
                                                        src="{{ asset($jenis->path_gambar) }}"
                                                        data-zoom="{{ asset($jenis->path_gambar) }}"
                                                        data-src="{{ asset($jenis->path_gambar) }}" alt="">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="container-swiper">
                                    <div class="swiper tf-product-media-thumbs other-image-zoom"
                                        data-direction="horizontal">
                                        <div class="swiper-wrapper stagger-wrap">
                                            @for ($i = 1; $i <= 3; $i++)
                                                @if ($produk->{"gambar_{$i}"})
                                                    <div class="swiper-slide stagger-item" data-color="gray">
                                                        <div class="item">
                                                            <img class="lazyload"
                                                                data-src="{{ asset($produk->{"gambar_{$i}"}) }}"
                                                                src="{{ asset($produk->{"gambar_{$i}"}) }}" alt="">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endfor

                                            @foreach ($produk->jenisProduk as $jenis)
                                                <div class="swiper-slide stagger-item" data-color="gray"
                                                    data-jenis-id="{{ $jenis->id_jenis_produk }}">
                                                    <div class="item">
                                                        <img class="lazyload" data-src="{{ asset($jenis->path_gambar) }}"
                                                            src="{{ asset($jenis->path_gambar) }}" alt="">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Product Image -->
                    </div>
                    <div class="col-md-6">
                        <!-- Product Infor -->
                        <div class="tf-product-info-wrap bg-white position-relative">
                            {{-- <div class="tf-zoom-main"></div> --}}
                            <div class="tf-product-info-list style-2 justify-content-xl-end">
                                <div class="tf-product-info-content">
                                    <div class="infor-heading">
                                        <p class="caption">Kategori:
                                            <a href="{{ route("home", ["kategori" => $produk->kategori->id_kategori] + request()->except("kategori")) }}"
                                                class="link text-secondary">
                                                {{ $produk->kategori->nama }}
                                            </a>
                                        </p>
                                        <h5 class="product-info-name fw-semibold">
                                            {{ $produk->nama }}
                                        </h5>
                                        <span class="body-text-3 caption text-muted">
                                            <strong class="text-success">{{ $produk->totalTerjual }}</strong>
                                            Terjual
                                        </span>
                                        @php
                                            // Tentukan stok awal yang ditampilkan: jenis terpilih -> jumlah pada jenis, jika tidak ada -> jumlah produk utama
                                            $initialStock = $produk->jumlah_produk ?? 0;
                                            if ($produk->jenisProduk->isNotEmpty()) {
                                                $selectedJenisId =
                                                    $produk->jenisProdukTerpilih ??
                                                    $produk->jenisProduk->first()->id_jenis_produk;
                                                $selectedJenis = $produk->jenisProduk->firstWhere(
                                                    "id_jenis_produk",
                                                    $selectedJenisId,
                                                );
                                                if ($selectedJenis) {
                                                    $initialStock = $selectedJenis->jumlah_produk ?? $initialStock;
                                                }
                                            }
                                        @endphp

                                        <p class="body-text-3 caption text-muted">
                                            Sisa Stok: <strong id="product-stock"
                                                class="text-primary">{{ $initialStock }}</strong>
                                        </p>
                                    </div>
                                    <div class="infor-center">
                                        <div class="product-info-price">
                                            <h4 class="text-primary">Rp. {{ number_format($produk->harga, 0, ",", ".") }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="tf-product-info-choose-option flex-xl-nowrap">
                                            <div class="product-quantity">
                                                <p class=" title body-text-3">
                                                    Jumlah
                                                </p>
                                                <div class="wg-quantity">
                                                    <button class="btn-quantity btn-decrease">
                                                        <i class="icon-minus"></i>
                                                    </button>
                                                    <input class="quantity-product" type="text" name="number"
                                                        value="1">
                                                    <button class="btn-quantity btn-increase">
                                                        <i class="icon-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($produk->jenisProduk->isNotEmpty())
                                                <div class="product-color">
                                                    <p class=" title body-text-3">
                                                        Jenis
                                                    </p>
                                                    <div class="tf-select-color ">
                                                        <select class="select-color">
                                                            @foreach ($produk->jenisProduk as $jenis)
                                                                <option value="{{ $jenis->id_jenis_produk }}"
                                                                    data-jumlah="{{ $jenis->jumlah_produk ?? 0 }}"
                                                                    {{ $jenis->id_jenis_produk == $produk->jenisProdukTerpilih ? "selected" : "" }}>
                                                                    {{ $jenis->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                            @auth
                                                <div class="product-box-btn">
                                                    <a href="#shoppingCart" data-bs-toggle="offcanvas"
                                                        class="tf-btn text-white btn-add-to-cart"
                                                        data-product-id="{{ $produk->id_produk }}"
                                                        data-product-nama="{{ $produk->nama }}"
                                                        data-product-harga="{{ $produk->harga }}"
                                                        data-product-gambar="{{ $produk->gambar_1 ? asset($produk->gambar_1) : asset("home/images/no-image.png") }}"
                                                        data-product-stok="{{ $initialStock }}"
                                                        data-product-kategori="{{ $produk->kategori->nama }}">
                                                        Tambah Keranjang
                                                        <i class="icon-cart-2"></i>
                                                    </a>
                                                </div>
                                                {{-- Tombol Langsung Checkout --}}
                                                <div class="product-box-btn ms-2">
                                                    <button type="button" class="tf-btn text-white btn-checkout-now"
                                                        data-product-id="{{ $produk->id_produk }}"
                                                        data-product-nama="{{ $produk->nama }}"
                                                        data-product-harga="{{ $produk->harga }}"
                                                        data-product-gambar="{{ $produk->gambar_1 ? asset($produk->gambar_1) : asset("home/images/no-image.png") }}"
                                                        data-product-stok="{{ $initialStock }}"
                                                        data-product-kategori="{{ $produk->kategori->nama }}">
                                                        Beli
                                                    </button>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                    <div class="infor-bottom">
                                        <h6 class="fw-semibold">Deskripsi</h6>
                                        {!! $produk->keterangan !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /Product Infor -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Product Main -->

    @push("scripts")
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectJenis = document.querySelector('.select-color');

                function selectJenisById(id) {
                    if (!selectJenis) return;
                    const option = selectJenis.querySelector('option[value="' + id + '"]');
                    if (option) {
                        selectJenis.value = id;
                        selectJenis.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                }

                if (selectJenis) {
                    selectJenis.addEventListener('change', function() {
                        const jenisId = this.value;
                        const swiperMain = document.querySelector('#gallery-swiper-started');
                        if (swiperMain && swiperMain.swiper) {
                            const slides = swiperMain.querySelectorAll('.swiper-slide');
                            let targetIndex = -1;
                            slides.forEach((slide, index) => {
                                if (slide.getAttribute('data-jenis-id') === jenisId) {
                                    targetIndex = index;
                                }
                            });
                            if (targetIndex !== -1) {
                                swiperMain.swiper.slideTo(targetIndex);
                            }
                        }
                    });
                }

                const thumbsContainer = document.querySelector('.tf-product-media-thumbs');
                if (thumbsContainer) {
                    thumbsContainer.addEventListener('click', function(e) {
                        const slide = e.target.closest('.swiper-slide');
                        if (!slide) return;
                        const jenisId = slide.getAttribute('data-jenis-id');
                        if (jenisId) {
                            selectJenisById(jenisId);
                        }
                    });
                }

                const mainSwiper = document.querySelector('#gallery-swiper-started');
                if (mainSwiper) {
                    mainSwiper.addEventListener('click', function(e) {
                        const slide = e.target.closest('.swiper-slide');
                        if (!slide) return;
                        const jenisId = slide.getAttribute('data-jenis-id');
                        if (jenisId) {
                            selectJenisById(jenisId);
                        }
                    });
                }

                function updateStockDisplay(stock) {
                    const el = document.getElementById('product-stock');
                    const value = typeof stock !== 'undefined' && stock !== null ? stock : 0;
                    if (el) el.textContent = value;
                    const btnAdd = document.querySelector('.btn-add-to-cart');
                    const btnCheckout = document.querySelector('.btn-checkout-now');
                    if (btnAdd) btnAdd.setAttribute('data-product-stok', value);
                    if (btnCheckout) btnCheckout.setAttribute('data-product-stok', value);
                    // Enable/disable buttons berdasarkan stok
                    const qtyInput = document.querySelector('.quantity-product');
                    const btnIncrease = document.querySelector('.btn-increase');
                    const btnDecrease = document.querySelector('.btn-decrease');
                    const intVal = parseInt(value, 10) || 0;

                    if (intVal <= 0) {
                        if (btnAdd) btnAdd.classList.add('disabled');
                        if (btnAdd) btnAdd.setAttribute('aria-disabled', 'true');
                        if (btnCheckout) btnCheckout.classList.add('disabled');
                        if (btnCheckout) btnCheckout.setAttribute('aria-disabled', 'true');
                        if (qtyInput) qtyInput.value = 0;
                    } else {
                        if (btnAdd) btnAdd.classList.remove('disabled');
                        if (btnAdd) btnAdd.removeAttribute('aria-disabled');
                        if (btnCheckout) btnCheckout.classList.remove('disabled');
                        if (btnCheckout) btnCheckout.removeAttribute('aria-disabled');
                        if (qtyInput && (parseInt(qtyInput.value, 10) || 0) < 1) qtyInput.value = 1;
                        // Jika kuantitas saat ini melebihi stok baru, sesuaikan dan beri peringatan
                        if (qtyInput && (parseInt(qtyInput.value, 10) > intVal)) {
                            alert('Jumlah yang Anda pilih melebihi sisa stok. Jumlah disesuaikan.');
                            qtyInput.value = intVal;
                        }
                    }
                }

                function updateCartImage() {
                    const btnAddToCart = document.querySelector('.btn-add-to-cart');
                    if (!btnAddToCart) return;

                    // If no jenis select, use first non-jenis slide image and keep product stock
                    if (!selectJenis) {
                        const swiperMain = document.querySelector('#gallery-swiper-started');
                        if (swiperMain) {
                            const firstSlide = swiperMain.querySelector('.swiper-slide:not([data-jenis-id])');
                            if (firstSlide) {
                                const img = firstSlide.querySelector('img');
                                if (img) {
                                    const imgSrc = img.getAttribute('src') || img.getAttribute('data-src');
                                    if (imgSrc) {
                                        btnAddToCart.setAttribute('data-product-gambar', imgSrc);
                                    }
                                }
                            }
                        }
                        const stok = btnAddToCart.getAttribute('data-product-stok') || 0;
                        updateStockDisplay(stok);
                        return;
                    }

                    const selectedJenisId = selectJenis.value;
                    let stokToSet = null;

                    if (selectedJenisId) {
                        // prefer data from option (we added data-jumlah)
                        const opt = selectJenis.querySelector('option[value="' + selectedJenisId + '"]');
                        if (opt) {
                            stokToSet = opt.dataset.jumlah ?? opt.getAttribute('data-jumlah');
                        }

                        // Cari slide gambar jenis dan set gambar keranjang & checkout
                        const swiperMain = document.querySelector('#gallery-swiper-started');
                        if (swiperMain) {
                            const targetSlide = swiperMain.querySelector('.swiper-slide[data-jenis-id="' +
                                selectedJenisId + '"]');
                            if (targetSlide) {
                                const img = targetSlide.querySelector('img');
                                if (img) {
                                    const imgSrc = img.getAttribute('src') || img.getAttribute('data-src');
                                    if (imgSrc) {
                                        if (btnAddToCart) btnAddToCart.setAttribute('data-product-gambar', imgSrc);
                                        const btnCheckout = document.querySelector('.btn-checkout-now');
                                        if (btnCheckout) btnCheckout.setAttribute('data-product-gambar', imgSrc);
                                    }
                                }
                            }
                        }
                    }

                    if (stokToSet === null) stokToSet = (btnAddToCart && btnAddToCart.getAttribute(
                        'data-product-stok')) || 0;
                    updateStockDisplay(stokToSet);
                }

                if (selectJenis) {
                    selectJenis.addEventListener('change', updateCartImage);
                }

                // Initial update
                updateCartImage();

                // --- Quantity & stock validation ---
                const qtyInput = document.querySelector('.quantity-product');
                const btnIncrease = document.querySelector('.btn-increase');
                const btnDecrease = document.querySelector('.btn-decrease');
                const btnAddToCart = document.querySelector('.btn-add-to-cart');
                const btnCheckoutNow = document.querySelector('.btn-checkout-now');

                function getCurrentStock() {
                    if (btnAddToCart) {
                        const v = btnAddToCart.getAttribute('data-product-stok');
                        const n = parseInt(v, 10);
                        return isNaN(n) ? 0 : n;
                    }
                    const el = document.getElementById('product-stock');
                    const n = el ? parseInt(el.textContent, 10) : 0;
                    return isNaN(n) ? 0 : n;
                }

                function sanitizeQtyInput() {
                    if (!qtyInput) return 1;
                    let val = parseInt(qtyInput.value, 10);
                    if (isNaN(val) || val < 0) val = 0;
                    qtyInput.value = val;
                    return val;
                }

                // NOTE: the theme already binds handlers for .btn-increase / .btn-decrease
                // in `public/home/js/main.js`. To avoid duplicate increments we don't
                // re-bind click handlers here. Instead validate the final quantity
                // after changes (on 'change' event) and clamp to stock.
                if (qtyInput) {
                    qtyInput.addEventListener('change', function() {
                        let qty = sanitizeQtyInput();
                        const stock = getCurrentStock();
                        if (qty > stock) {
                            alert('Jumlah yang Anda masukkan melebihi stok.');
                            qtyInput.value = stock;
                        }
                        if (qty < 1 && stock > 0) {
                            qtyInput.value = 1;
                        }
                    });
                }

                if (btnAddToCart) {
                    btnAddToCart.addEventListener('click', function(e) {
                        const stock = getCurrentStock();
                        let qty = sanitizeQtyInput();
                        // If stock is zero or requested qty > stock, prevent adding
                        if (stock <= 0) {
                            e.preventDefault();
                            e.stopPropagation();
                            alert('Stok habis. Tidak dapat menambahkan ke keranjang.');
                            return false;
                        }
                        if (qty < 1) {
                            e.preventDefault();
                            e.stopPropagation();
                            alert('Jumlah minimal adalah 1.');
                            qtyInput.value = 1;
                            return false;
                        }
                        if (qty > stock) {
                            e.preventDefault();
                            e.stopPropagation();
                            alert('Stok tidak cukup. Sisa stok: ' + stock);
                            qtyInput.value = stock;
                            return false;
                        }
                        // ok
                        return true;
                    });
                }
                // Checkout sekarang: validasi lalu redirect ke halaman checkout dengan param
                if (btnCheckoutNow) {
                    btnCheckoutNow.addEventListener('click', function(e) {
                        const stock = getCurrentStock();
                        let qty = sanitizeQtyInput();
                        if (stock <= 0) {
                            alert('Stok habis. Tidak dapat melakukan checkout.');
                            return false;
                        }
                        if (qty < 1) {
                            alert('Jumlah minimal adalah 1.');
                            if (qtyInput) qtyInput.value = 1;
                            return false;
                        }
                        if (qty > stock) {
                            alert('Stok tidak cukup. Sisa stok: ' + stock);
                            if (qtyInput) qtyInput.value = stock;
                            return false;
                        }

                        // Trigger existing Add to Cart behavior (which populates cart),
                        // then redirect user to the real checkout page which reads from cart.
                        const addBtn = document.querySelector('.btn-add-to-cart');
                        if (addBtn) {
                            // Ensure addBtn has the current qty set (some implementations read qty from page)
                            addBtn.setAttribute('data-product-qty', qty);
                            // Trigger click to reuse existing add-to-cart logic
                            addBtn.click();

                            // Wait briefly for add-to-cart to complete, then redirect to checkout
                            setTimeout(function() {
                                window.location.href = '{{ route("checkout") }}';
                            }, 700);
                            return true;
                        }

                        // Fallback: if add-to-cart button not found, just redirect to checkout
                        window.location.href = '{{ route("checkout") }}';
                        return true;
                    });
                }
            });
        </script>
    @endpush
@endsection

@section("footer")
    @include("partials.footer")
@endsection
