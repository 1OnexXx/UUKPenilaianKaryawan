<x-layout.main>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <x-slot name="title">
        Pelaporan Kinerja
    </x-slot>
    <section class="section">
        <div class="card">
            @php
                $role = Auth::user()->role;
            @endphp
            <div x-data="{ openModal: false }">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Pelaporan Kinerja Datatable</span>

                    @if ($role == 'karyawan')
                        <a href="{{ route('karyawan.pelaporan.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Tambah Data
                        </a>

                        <!-- Trigger Modal -->
                        <button @click="openModal = true" class="btn btn-primary">
                            Buat Pelaporan Otomatis
                        </button>
                    @endif
                </div>

                <div x-show="openModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div @click.away="openModal = false"
                        class="bg-white rounded-lg shadow-xl w-full max-w-lg p-5 transform transition-all"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <!-- Isi Modal -->
                        <form action="{{ route($role . '.pelaporan.storeOtomatis') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="flex justify-between items-center border-b pb-2 mb-4">
                                <h2 class="text-lg font-semibold">Buat Pelaporan Otomatis</h2>
                                <button type="button" @click="openModal = false"
                                    class="text-gray-500 hover:text-gray-800">✖</button>
                            </div>

                            <div class="mb-4">
                                <label for="lampiran" class="block text-sm font-medium text-gray-700">
                                    Upload Lampiran (Opsional)
                                </label>
                                <input type="file" name="lampiran[]" id="lampiran" multiple
                                    class="mt-1 block w-full border border-gray-300 rounded shadow-sm p-2">
                                <p class="text-xs text-gray-500 mt-1">
                                    Boleh dikosongkan jika tidak ada file yang ingin dilampirkan.
                                </p>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="openModal = false"
                                    class="btn btn-outline-secondary">Batal</button>
                                <button type="submit" class="btn btn-success">Kirim Pelaporan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
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
                                <th>lampiran</th>
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
                                        @if ($p->lampiran2->count() > 0)
                                            @foreach ($p->lampiran2 as $lampiran)
                                                @php
                                                    $fileExtension = pathinfo($lampiran->file_path, PATHINFO_EXTENSION);
                                                @endphp

                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                    <a href="{{ Storage::url($lampiran->file_path) }}"
                                                        class="btn btn-sm btn-info" target="_blank">
                                                        <i class="bi bi-eye"></i> Lihat Gambar
                                                    </a>
                                                @elseif (in_array($fileExtension, ['pdf']))
                                                    <a href="{{ Storage::url($lampiran->file_path) }}"
                                                        class="btn btn-sm btn-info" target="_blank">
                                                        <i class="bi bi-eye"></i> Lihat PDF
                                                    </a>
                                                @elseif (in_array($fileExtension, ['mp4']))
                                                    <a href="{{ Storage::url($lampiran->file_path) }}"
                                                        class="btn btn-sm btn-info" target="_blank">
                                                        <i class="bi bi-eye"></i> Lihat Video
                                                    </a>
                                                @else
                                                    <a href="{{ Storage::url($lampiran->file_path) }}"
                                                        class="btn btn-sm btn-info" target="_blank">
                                                        <i class="bi bi-eye"></i> Lihat Lampiran
                                                    </a>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada lampiran</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($role == 'karyawan')
                                            <a href="{{ route('karyawan.pelaporan.edit', $p->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('karyawan.pelaporan.delete', $p->id) }}"
                                                method="POST" class="d-inline"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route($role . '.pelaporan.review', $p->id) }}"
                                                class="btn btn-sm btn-info">Lihat detail</a>
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
