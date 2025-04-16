<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <x-slot name="title">
        Pelaporan Kinerja
    </x-slot>
    <section class="section">
        <div class="card">
            @php
                $role = Auth::user()->role;
            @endphp
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pelaporan Kinerja Datatable</span>
                @if ($role == 'karyawan')
                    <a href="{{ route('karyawan.pelaporan.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Data
                    </a>
                @endif
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
                            <th>periode</th>
                            <th>isi laporan</th>
                            <th>status</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelaporan as $p)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $p->karyawan->user->nama_lengkap }}</td>
                                <td>{{ $p->karyawan->user->email }}</td>
                                <td>{{ $p->periode }}</td>
                                <td>{{ $p->isi_laporan }}</td>
                                <td>{{ $p->status }}</td>
                                <td>
                                    @if ($role == 'karyawan')
                                        <a href="{{ route('karyawan.pelaporan.edit', $p->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('karyawan.pelaporan.delete', $p->id) }}" method="POST"
                                            class="d-inline"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                    <a href="{{route($role . '.pelaporan.review', $p->id)}}" class="btn btn-sm btn-info">Lihat detail</a>
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
