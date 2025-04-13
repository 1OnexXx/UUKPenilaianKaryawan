<x-layout.main>
    <x-slot name="title">
        Edit Pengguna
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Pengguna</h4>
                <a href="{{ route('admin.manajemen_pengguna') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.manajemen_pengguna.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Nama Lengkap --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                    value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                                @error('nama_lengkap') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        {{-- Password (opsional) --}}
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="password">Password <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Minimal 8 karakter">
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="role">Peran</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option disabled value="">Pilih peran</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="karyawan" {{ old('role', $user->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="tim_penilai" {{ old('role', $user->role) == 'tim_penilai' ? 'selected' : '' }}>Tim Penilai</option>
                                    <option value="kepala_sekolah" {{ old('role', $user->role) == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                </select>
                                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.manajemen_pengguna') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout.main>
