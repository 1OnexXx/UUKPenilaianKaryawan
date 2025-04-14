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
            <label>Periode (misal: Januari - Juni 2025)</label>
            <input type="text" name="periode" class="form-control" required>
        </div>@error('periode')
            <div class="alert alert-danger">{{ $message }}</div>
            
        @enderror
    
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