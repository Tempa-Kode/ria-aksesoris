<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="index.php" class="sidebar-logo">
            <img src="{{ asset("dashboard/assets/images/logo.png") }}" alt="site logo" class="light-logo">
            <img src="{{ asset("dashboard/assets/images/logo-light.png") }}" alt="site logo" class="dark-logo">
            <img src="{{ asset("dashboard/assets/images/logo-icon.png") }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="{{ Route::currentRouteName() === "dashboard" ? "active-page" : "" }}">
                <a href="{{ route("dashboard") }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="email.php">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Data Kategori</span>
                </a>
            </li>
            <li>
                <a href="chat-message.php">
                    <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                    <span>Data Produk</span>
                </a>
            </li>
            <li>
                <a href="calendar-main.php">
                    <iconify-icon icon="solar:calendar-outline" class="menu-icon"></iconify-icon>
                    <span>Data Customer</span>
                </a>
            </li>
            <li>
                <a href="kanban.php">
                    <iconify-icon icon="material-symbols:map-outline" class="menu-icon"></iconify-icon>
                    <span>Transaksi/Pesanan</span>
                </a>
            </li>
            <li>
                <a href="kanban.php">
                    <iconify-icon icon="material-symbols:map-outline" class="menu-icon"></iconify-icon>
                    <span>Laporan Penjualan</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Data Pengguna</li>
            <li class="{{ request()->routeIs("admin.*") ? "active-page" : "" }}">
                <a href="{{ route("admin.index") }}">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Data Admin</span>
                </a>
            </li>
            <li class="{{ request()->routeIs("karyawan.*") ? "active-page" : "" }}">
                <a href="{{ route("karyawan.index") }}">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Data Karyawan</span>
                </a>
            </li>
            <li>
                <a href="kanban.php">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Data Customer</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
