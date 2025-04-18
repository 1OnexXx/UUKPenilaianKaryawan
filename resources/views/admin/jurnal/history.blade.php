<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <x-slot name="title">
        History Jurnal Harian
    </x-slot>
    <section class="section">
        <div class="card">
            @php
                $role = Auth::user()->role;
            @endphp
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Jurnal Datatable</span>
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
                            <th>tanggal</th>
                            <th>uraian</th>
                            <th>lampiran</th>
                            <th>status</th>
                            <th>komentar</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jurnal as $j)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $j->karyawan->user->nama_lengkap }}</td>
                                <td>{{ $j->karyawan->user->email }}</td>
                                <td>{{ $j->tanggal }}</td>
                                <td>{{ $j->uraian }}</td>
                                <td>
                                    @if ($j->lampiran->count() > 0)
                                        <a href="{{ route($role . '.jurnal.lampiran', $j->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Lihat Lampiran
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada lampiran</span>
                                    @endif

                                </td>
                                <td>{{ $j->status }}</td>
                                <td>{{ $j->komentar }}</td>

                                <td>
                                    @if ($j->status == 'ditolak')
                                        <a href="{{ route($role . '.jurnal.edit', $j->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                            revisi
                                        </a>
                                        <form action="{{ route($role . '.jurnal.delete', $j->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')">
                                                <i class="bi bi-trash"></i>
                                                hapus
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route($role . '.jurnal.delete', $j->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')">
                                                <i class="bi bi-trash"></i>
                                                hapus
                                            </button>
                                        </form>
                                    @endif
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
