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
                                        @forelse ($produk->gambarProduk as $gambar)
                                            <div class="swiper-slide" data-color="gray">
                                                <a href="{{ asset($gambar->path_gambar) }}" target="_blank" class="item"
                                                    data-pswp-width="600px" data-pswp-height="800px">
                                                    <img class="tf-image-zoom lazyload"
                                                        src="{{ asset($gambar->path_gambar) }}"
                                                        data-zoom="{{ asset($gambar->path_gambar) }}"
                                                        data-src="{{ asset($gambar->path_gambar) }}" alt="">
                                                </a>
                                            </div>
                                        @empty
                                            <p>No images available</p>
                                        @endforelse

                                        @foreach ($produk->jenisProduk as $jenis)
                                            <div class="swiper-slide" data-color="gray">
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
                                            @foreach ($produk->gambarProduk as $gambar)
                                                <div class="swiper-slide stagger-item" data-color="gray">
                                                    <div class="item">
                                                        <img class="lazyload" data-src="{{ asset($gambar->path_gambar) }}"
                                                            src="{{ asset($gambar->path_gambar) }}" alt="">
                                                    </div>
                                                </div>
                                            @endforeach

                                            @foreach ($produk->jenisProduk as $jenis)
                                                <div class="swiper-slide stagger-item" data-color="gray">
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
                            <div class="tf-zoom-main"></div>
                            <div class="tf-product-info-list style-2 justify-content-xl-end">
                                <div class="tf-product-info-content">
                                    <div class="infor-heading">
                                        <p class="caption">Kategori:
                                            <a href="{{ route("home", ["kategori" => $produk->kategori->id] + request()->except("kategori")) }}"
                                                class="link text-secondary">
                                                {{ $produk->kategori->nama }}
                                            </a>
                                        </p>
                                        <h5 class="product-info-name fw-semibold">
                                            {{ $produk->nama }}
                                        </h5>
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
                                            @if ($produk->jenisProduk)
                                                <div class="product-color">
                                                    <p class=" title body-text-3">
                                                        Jenis
                                                    </p>
                                                    <div class="tf-select-color ">
                                                        <select class="select-color">
                                                            @foreach ($produk->jenisProduk as $jenis)
                                                                <option value="{{ $jenis->id }}"
                                                                    {{ $jenis->id == $produk->jenisProdukTerpilih ? "selected" : "" }}>
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
                                                        data-product-id="{{ $produk->id }}"
                                                        data-product-nama="{{ $produk->nama }}"
                                                        data-product-harga="{{ $produk->harga }}"
                                                        data-product-gambar="{{ $produk->gambarProduk->first() ? asset($produk->gambarProduk->first()->path_gambar) : asset("home/images/no-image.png") }}"
                                                        data-product-kategori="{{ $produk->kategori->nama }}">
                                                        Tambah Keranjang
                                                        <i class="icon-cart-2"></i>
                                                    </a>
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
@endsection
