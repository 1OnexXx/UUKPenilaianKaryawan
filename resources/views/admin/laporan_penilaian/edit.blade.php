<x-layout.main>
    <x-slot name="title">
        Edit Laporan Penilaian
    </x-slot>
    @php
        $role = Auth::user()->role;
    @endphp
    <form action="{{ route($role . '.laporan_penilaian.update', $laporan->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Pastikan menggunakan PUT untuk update -->

        <div class="form-group">
            <label>Karyawan</label>
            <select name="karyawan_id" class="form-control" required>
                @foreach ($karyawan as $item)
                    <option value="{{ $item->id }}" {{ $laporan->karyawan_id == $item->id ? 'selected' : '' }}>
                        {{ $item->user->nama_lengkap }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('karyawan_id')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label>Jenis Laporan</label>
            <select name="jenis_laporan" class="form-control" required>
                <option value="bulanan" {{ $laporan->jenis_laporan == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="semester" {{ $laporan->jenis_laporan == 'semester' ? 'selected' : '' }}>Semester</option>
                <option value="tahunan" {{ $laporan->jenis_laporan == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
            </select>
        </div>
        @error('jenis_laporan')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label>Periode (misal: Januari - Juni 2025)</label>
            <input type="text" name="periode" class="form-control" value="{{ old('periode', $laporan->periode) }}" required>
        </div>
        @error('periode')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label>Rekomendasi</label>
            <textarea name="rekomendasi" class="form-control">{{ old('rekomendasi', $laporan->rekomendasi) }}</textarea>
        </div>
        @error('rekomendasi')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary">Perbarui Laporan</button>
    </form>
</x-layout.main>
