<x-layout.main>
    <x-slot name="title">Profil Pengguna</x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Edit Profil</h4>
            </div>
            <div class="card-body">
                @php
                    $user = Auth::user();
                    $role = $user->role;
                @endphp

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ $user->nama_lengkap }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>

                    @if ($role === 'karyawan')
                        <div class="mb-3">
                            <label class="form-label">Divisi</label>
                            <select name="divisi_id" class="form-select">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisis as $divisi)
                                    <option value="{{ $divisi->id }}"
                                        {{ ($user->karyawan->divisi_id ?? '') == $divisi->id ? 'selected' : '' }}>
                                        {{ $divisi->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control"
                                value="{{ $user->karyawan->nip ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control"
                                value="{{ $user->karyawan->jabatan ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="{{ $user->karyawan->no_hp ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control"
                                value="{{ $user->karyawan->tanggal_masuk ?? '' }}">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout.main>
