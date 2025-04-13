<div id="sidebar" class='active'>
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <img src="assets/images/logo.svg" alt="" srcset="">
        </div>
        <div class="sidebar-menu">
            <ul class="menu">

                <li class='sidebar-title'>Main Menu</li>



                <li class="sidebar-item active ">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i data-feather="home" width="20"></i>
                        <span>Dashboard</span>
                    </a>

                </li>

                @php
                    $role = Auth::user()->role;
                @endphp

                <!-- sidebar admin  -->
                @if (Auth::user()->role == 'admin')
                    <li class='sidebar-title'>Data Master</li>

                    <li class="sidebar-item">
                        <a href="{{ route($role . '.divisi') }}" class='sidebar-link'>
                            <i data-feather="layers" width="20"></i>
                            <span>Divisi</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route($role . '.kategori_penilaian') }}" class='sidebar-link'>
                            <i data-feather="list" width="20"></i>
                            <span>Kategori Penilaian</span>
                        </a>
                    </li>

                    <li class='sidebar-title'>Manajemen</li>

                    <li class="sidebar-item">
                        <a href="{{ route($role . '.manajemen_pengguna') }}" class='sidebar-link'>
                            <i data-feather="users" width="20"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route($role . '.karyawan') }}" class='sidebar-link'>
                            <i data-feather="user-check" width="20"></i>
                            <span>Karyawan</span>
                        </a>
                    </li>

                    <li class='sidebar-title'>Laporan</li>

                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i data-feather="book-open" width="20"></i>
                            <span>Jurnal Harian</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i data-feather="bar-chart-2" width="20"></i>
                            <span>Laporan Kinerja</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i data-feather="clipboard" width="20"></i>
                            <span>Laporan Penilaian</span>
                        </a>
                    </li>
                @endif

                <!-- sidebar admin end -->

                <!-- sidebar karyawan  -->
                @if (Auth::user()->role == 'karyawan')
                    <li class="sidebar-item">
                        <a href="{{ route($role . '.jurnal') }}" class='sidebar-link'>
                            <i data-feather="book-open" width="20"></i>
                            <span>Jurnal Harian</span>
                        </a>
                    </li>


                    <li class="sidebar-item">
                        <a href="{{ route($role . '.pelaporan') }}" class='sidebar-link'>
                            <i data-feather="bar-chart-2" width="20"></i>
                            <span>Laporan Kinerja</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i data-feather="check-square" width="20"></i>
                            <span>Penilaian Saya</span>
                        </a>
                    </li>


                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i data-feather="clipboard" width="20"></i>
                            <span>Laporan Penilaian</span>
                        </a>
                    </li>
                @endif
                <!-- sidebar karyawan end  -->



                <!-- sidebar tim Penilaian -->
                @if (Auth::user()->role == 'tim_penilai')
                    <li class="sidebar-title">Penilaian</li>

                    <li class="sidebar-item has-sub">
                        <a href="#" class="sidebar-link">
                            <i data-feather="clipboard-check" width="20"></i>
                            <span>Penilaian Karyawan</span>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route($role . '.karyawan') }}">Daftar Karyawan</a>
                            </li>
                            <li>
                                <a href="{{ route($role . '.riwayat_penilaian') }}">Penilaian</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route($role . '.kategori_penilaian') }}" class="sidebar-link">
                            <i data-feather="list" width="20"></i>
                            <span>Kategori Penilaian</span>
                        </a>
                    </li>
                @endif
                <!-- sidebar tim Penilaian end -->




                <!-- sidebar kepala sekolah  -->

                @if (Auth::user()->role == 'kepala_sekolah')
                    <li class="sidebar-item">
                        <a href="{{ route($role . '.laporan_penilaian') }}" class='sidebar-link'>
                            <i data-feather="clipboard" width="20"></i>
                            <span>Laporan Penilaian</span>
                        </a>
                    </li>


                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i data-feather="bar-chart-2" width="20"></i>
                            <span>Pelaporan Kinerja</span>
                        </a>
                    </li>


                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i data-feather="trending-up" width="20"></i>
                            <span>Monitoring</span>
                        </a>
                    </li>
                @endif

                <!-- sidebar kepala sekolah end  -->




            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>

