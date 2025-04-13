<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <x-slot name="title">
        Manajemen Karyawan
    </x-slot>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Karyawan Datatable</span>
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
                            <th>nama lengkap</th>
                            <th>email</th>
                            <th>divisi</th>
                            <th>nip</th>
                            <th>jabatan</th>
                            <th>no hp</th>
                            <th>tanggal masuk</th>
                            <th>status</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawan as $k )
                        <tr>
                            <td>{{ $loop->iteration }} </td>
                            <td>{{ $k->user->nama_lengkap }}</td>
                            <td>{{ $k->user->email }}</td>
                            <td>{{ $k->divisi->nama_divisi ?? '-' }}</td>
                            <td>{{ $k->nip ?? '-' }}</td>
                            <td>{{ $k->jabatan ?? '-' }}</td>
                            <td>{{ $k->no_hp ?? '-' }}</td>
                            <td>{{ $k->tanggal_masuk ?? '-' }}</td>
                            <td>{{ $k->status }}</td>
                            <td>
                                @if (Auth::user()->role == 'admin')
                                <a href="{{ route('admin.karyawan.edit', $k->id) }}"  class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                    lengkapi 
                                </a>
                                <a href="{{ route('admin.karyawan.show', $k->id) }}"  class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                    lihat detail
                                </a>
                                <form action="{{ route('admin.karyawan.delete', $k->id) }}" method="POST" class="d-inline" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>
                                </form>
                                    @else
                                    <a href="{{ route('penilaian.create', $k->id) }}" class="btn btn-sm btn-primary">Beri Penilaian</a>
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
