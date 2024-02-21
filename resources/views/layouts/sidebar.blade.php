<aside class="main-sidebar sidebar-light-primary elevation-4">
    <a href="{{asset('index3.html')}}" class="brand-link" style="display: flex; align-items: center;">
        <img src="{{asset('logo[1].png')}}" alt="Logo" class="brand-image" style="opacity: .8; width: 30px; height: 50px;">
        <span style="flex: 1; display: flex; flex-direction: column; font-size:14px;">
          <strong>SMA Plus</strong>
          <span>DarulHikmah</span>
        </span>
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
                        <a href="/admin/class" class="nav-link">
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
                        <a href="/admin/qna-ans" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Soal</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/admin/exam" class="nav-link">
                    <i class="fas fa-file nav-icon"></i>
                    <p>Ujian</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/review-exams" class="nav-link">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>Hasil Ujian</p>
                </a>
            </li>
            <li class="nav-item">
              <a href="/logout" class="nav-link">
                <i class="fas fa fa-sign-in-alt mr-2 nav-icon"></i>
                <p>Logout</p>
              </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
  </aside>
