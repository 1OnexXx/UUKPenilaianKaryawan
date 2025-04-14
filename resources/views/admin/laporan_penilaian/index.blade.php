<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <x-slot name="title">
        Laporan Penilaian
    </x-slot>
    @php
        $role = Auth::user()->role;
    @endphp
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Laporan Penilaian Kinerja Datatable</span>
                <div>
                    <div class="modal-danger mr-1 mb-1 d-inline-block">

                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#danger">
                            generate laporan
                        </button>

                        <div class="modal fade text-left" id="danger" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel120" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title white" id="myModalLabel120">generate laporan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Tombol untuk download PDF -->
                                        <form action="{{ route('laporan.generate') }}" method="GET" class="mt-4">
                                            <input type="hidden" name="download_pdf" value="true">
                                            <div class="form-group">
                                                <label>Jenis Laporan</label>
                                                <select name="jenis_laporan" class="form-control" required>
                                                    <option value="bulanan">Bulanan</option>
                                                    <option value="semester">Semester</option>
                                                    <option value="tahunan">Tahunan</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success">Download PDF</button>
                                        </form>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route($role . '.laporan_penilaian.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                @endif
                <table class='table table-striped' id="table1">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>nama karyawan</th>
                            <th>email</th>
                            <th>jenis laporan</th>
                            <th>rekomendasi</th>
                            <th>periode</th>
                            <th>dibuat oleh</th>
                            <th>rata rata nilai</th>
                            <th>action</th>
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
                                <td>
                                    <a href="{{ route($role . '.laporan_penilaian.edit', $p->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route($role . '.laporan_penilaian.delete', $p->id) }}"
                                        method="POST" class="d-inline"
                                        onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
