<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link <?php echo request()->path() == '/' ? '' : 'collapsed'; ?>" href="/">
        <i class="bi bi-grid"></i>
        <span>Home</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo request()->path() == 'keranjang' ? '' : 'collapsed'; ?>" href="/keranjang">
        <i class="bi bi-cart-check"></i>
        <span>Keranjang</span>
      </a>
    </li>

    <?php
    if (session()->get('role') == 'admin') {
    ?>
        <li class="nav-item">
          <a class="nav-link <?php echo request()->path() == 'produk' ? '' : 'collapsed'; ?>" href="/produk">
            <i class="bi bi-receipt"></i>
            <span>Produk</span>
          </a>
        </li><!-- End Dashboard Nav -->
    <?php
    }
    ?>


  </ul>

</aside><!-- End Sidebar-->