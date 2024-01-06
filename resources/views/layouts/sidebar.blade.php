<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{asset('index3.html')}}" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="/admin/dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                    Master Data
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/admin/subject" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Mata Pelajaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/students" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Siswa/I</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../charts/uplot.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Soal</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="../charts/uplot.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Ujian</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../charts/uplot.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Hasil Ujian</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../examples/profile.html" class="nav-link">
                    <i class="far fa-gear nav-icon"></i>
                  <p>Pengaturan</p>
                </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
  </aside>
