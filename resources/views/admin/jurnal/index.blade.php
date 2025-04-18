<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <x-slot name="title">
        Jurnal Harian
    </x-slot>
    <section class="section">
        <div class="card">
            @php
                $role = Auth::user()->role;
            @endphp
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Jurnal Datatable</span>

                <a href="{{ route($role . '.jurnal.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Tambah Data
                </a>

                <form action="{{ route('karyawan.jurnal.approveAll') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin menyetujui semua jurnal?')">Kirim Semua</button>

                </form>
                

            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
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
                            <th>judul</th>
                            <th>uraian</th>
                            <th>tanggal</th>
                            <th>lampiran</th>
                            <th>status</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jurnal as $j)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $j->karyawan->user->nama_lengkap }}</td>
                                <td>{{ $j->karyawan->user->email }}</td>
                                <td>{{ $j->judul }}</td>
                                <td>{{ $j->uraian }}</td>
                                <td>{{ $j->tanggal }}</td>
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
                                <td>
                                @if ($j->status == 'draft')
                                <form action="{{ route($role . '.jurnal.approve', $j->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check"></i>
                                        kirim
                                    </button>
                                </form>
                                    @else
                                    {{ $j->status }}
                                @endif
                                </td>

                                <td>
                                    <a href="{{ route($role . '.jurnal.edit', $j->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route($role . '.jurnal.delete', $j->id) }}" method="POST"
                                        class="d-inline"
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
