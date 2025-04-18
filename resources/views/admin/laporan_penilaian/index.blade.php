<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">

    <x-slot name="title">
        Laporan Penilaian
    </x-slot>

    @php
        $role = Auth::user()->role;
    @endphp

    <section class="section py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="h5 mb-0">Laporan Penilaian Kinerja</span>
                <div>
                    <form action="{{ route('laporan.preview') }}" method="GET" class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mt-3 mt-md-0">
                        <div class="form-group">
                            <label for="jenis_laporan" class="form-label">Pilih Jenis Laporan</label>
                            <select name="jenis_laporan" id="jenis_laporan" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jenis Laporan --</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="semester">Semester</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="divisi" class="form-label">Pilih Divisi</label>
                            <select name="divisi" id="divisi" class="form-control">
                                <option value="all" selected>-- Semua Divisi --</option>
                                @foreach($divisiList as $divisi)
                                    <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-eye"></i> Preview Laporan
                        </button>
                    </form>

                    <a href="{{ route($role . '.laporan_penilaian.create') }}" class="btn btn-primary mt-3 mt-md-0">
                        <i class="bi bi-plus"></i> Tambah Data
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span><br>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama Karyawan</th>
                                <th>Email</th>
                                <th>Jenis Laporan</th>
                                <th>Rekomendasi</th>
                                <th>Periode</th>
                                <th>Dibuat Oleh</th>
                                <th>Rata-rata Nilai</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $p)
                                <tr>
                                    <td>{{ $loop->iteration }} </td>
                                    <td>{{ $p->karyawan->user->nama_lengkap }}</td>
                                    <td>{{ $p->karyawan->user->email }}</td>
                                    <td>{{ $p->jenis_laporan }}</td>
                                    <td>{{ $p->rekomendasi }}</td>
                                    <td>{{ $p->periode }}</td>
                                    <td>{{ $p->dibuatOleh->nama_lengkap }}</td>
                                    <td>{{ $p->rata_rata_nilai }}</td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route($role . '.laporan_penilaian.edit', $p->id) }}" class="btn btn-sm btn-warning">
                                           <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form action="{{ route($role . '.laporan_penilaian.delete', $p->id) }}"
                                              method="POST" class="d-inline"
                                              onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</x-layout.main>
