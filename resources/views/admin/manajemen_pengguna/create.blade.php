<x-layout.main>
    <x-slot name="title">
        Tambah Pengguna
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Tambah Pengguna</h4>
                <a href="{{ route('admin.manajemen_pengguna') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.manajemen_pengguna.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        {{-- Nama Lengkap --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Contoh: John Doe" required>
                            </div>
                        </div>
                        @error('nama_lengkap')
                            <small>{{ $message }}</small>
                        @enderror

                        {{-- Email --}}
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Contoh: john@example.com" required>
                            </div>
                        </div>
                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror

                        {{-- Password --}}
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" required>
                            </div>
                        </div>
                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror

                        {{-- Role --}}
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="role">Peran</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="" disabled selected>Pilih peran</option>
                                    <option value="admin">Admin</option>
                                    <option value="karyawan">karyawan</option>
                                    <option value="tim_penilai">Tim Penilaian</option>
                                    <option value="kepala_sekolah">Kepala sekolah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @error('role')
                            <small>{{ $message }}</small>
                        @enderror

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout.main>
