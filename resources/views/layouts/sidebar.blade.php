<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <!-- SVG logo -->
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">Konveksi</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <a href="{{ url('/dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-grid-alt"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <!-- Admin -->
    <li class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
      <a href="{{ url('/admin') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-check"></i>
        <div>Admin</div>
      </a>
    </li>

    <!-- Customer -->
    <li class="menu-item {{ request()->is('customer') ? 'active' : '' }}">
      <a href="{{ url('/customer') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Customer</div>
      </a>
    </li>

    <!-- Produk -->
    <li class="menu-item {{ request()->is('produk') ? 'active' : '' }}">
      <a href="{{ url('/produk') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-package"></i>
        <div>Produk</div>
      </a>
    </li>

    <!-- Pesanan -->
    <li class="menu-item {{ request()->is('pesanan') ? 'active' : '' }}">
      <a href="{{ url('/pesanan') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-cart"></i>
        <div>Pesanan</div>
      </a>
    </li>

    <!-- Pengiriman -->
    <li class="menu-item {{ request()->is('pengiriman') ? 'active' : '' }}">
      <a href="{{ url('/pengiriman') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-truck"></i>
        <div>Pengiriman</div>
      </a>
    </li>

  </ul>
</aside>