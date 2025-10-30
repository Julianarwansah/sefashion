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
        <i class="menu-icon tf-icons bx bx-home-alt"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
      <a href="{{ url('/admin') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-alt"></i>
        <div>Admin</div>
      </a>
    </li>

    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('customer') ? 'active' : '' }}">
      <a href="{{ url('/customer') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-alt"></i>
        <div>Customer</div>
      </a>
    </li>

    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('produk') ? 'active' : '' }}">
      <a href="{{ url('/produk') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-alt"></i>
        <div>Produk</div>
      </a>
    </li>

    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('pesanan') ? 'active' : '' }}">
      <a href="{{ url('/pesanan') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-alt"></i>
        <div>Pesanan</div>
      </a>
    </li>
  </ul>
</aside>
