<x-layout.main>
    <x-slot name="title">
        Laporan Penilaian
    </x-slot>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $role = Auth::user()->role;
    @endphp

    <form action="{{ route($role . '.laporan_penilaian.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Karyawan</label>
            <select name="karyawan_id" class="form-control" required>
                @foreach ($karyawan as $item)
                    <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        @error('karyawan_id')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label>Jenis Laporan</label>
            <select name="jenis_laporan" class="form-control" required>
                <option value="bulanan">Bulanan</option>
                <option value="semester">Semester</option>
                <option value="tahunan">Tahunan</option>
            </select>
        </div>
        @error('jenis_laporan')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="periode">Periode</label>
            <div class="d-flex gap-2">
                <select name="bulan_awal" class="form-control" required>
                    <option value="">Dari Bulan</option>
                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                        <option value="{{ $bulan }}">{{ $bulan }}</option>
                    @endforeach
                </select>

                <select name="bulan_akhir" class="form-control" required>
                    <option value="">Sampai Bulan</option>
                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                        <option value="{{ $bulan }}">{{ $bulan }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="form-control" required>
                    <option value="">Tahun</option>
                    @for ($year = now()->year; $year >= now()->year - 10; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
        </div>



        <div class="form-group">
            <label>Rekomendasi</label>
            <textarea name="rekomendasi" class="form-control"></textarea>
        </div>
        @error('rekomendasi')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary">Buat Laporan</button>
    </form>

</x-layout.main>
