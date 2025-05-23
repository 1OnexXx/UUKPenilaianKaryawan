<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <x-slot name="title">
        Penilaian karyawan
    </x-slot>
    <section class="section">
        @php
            $role = Auth::user()->role;
        @endphp
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Penilaian Karyawna Datatable</span>
                @if ($role == 'tim_penilai')
                    <a href="{{ route('tim_penilai.karyawan') }}" class="btn btn-primary"><i class="bi bi-plus"></i>
                        Tambah Data"></a>
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
                            <th>nama penilai</th>
                            <th>email penilai</th>
                            <th>kategori Penilaian</th>
                            <th>nilai</th>
                            <th>komentar</th>
                            <th>periode</th>
                            @if ($role == 'tim_penilai')
                                <th>action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilaian as $p)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $p->karyawan->user->nama_lengkap }}</td>
                                <td>{{ $p->karyawan->user->email }}</td>
                                <td>{{ $p->penilai->nama_lengkap }}</td>
                                <td>{{ $p->penilai->email }}</td>
                                <td>{{ $p->kategori->nama_kategori }}</td>
                                <td>{{ $p->nilai }}</td>
                                <td>{{ $p->komentar }}</td>
                                <td>{{ $p->periode }}</td>
                                <td>
                                    @if ($role == 'tim_penilai')
                                        {{-- <a href="{{ route('tim_penilai.riwayat_penilaian.edit', $p->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a> --}}
                                        <form action="{{ route('tim_penilai.riwayat_penilaian.delete', $p->id) }}"
                                            method="POST" class="d-inline"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                                Hapus
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
