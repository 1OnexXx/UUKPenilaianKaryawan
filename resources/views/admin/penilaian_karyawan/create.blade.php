<x-layout.main>
    <x-slot name="title">Form Penilaian</x-slot>

    <div class="card">
        <div class="card-header">
            Form Penilaian untuk: <strong>{{ $karyawan->user->nama_lengkap }}</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('tim_penilai.riwayat_penilaian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">

                <div class="form-group">
                    <label for="kategori">Kategori Penilaian</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="" disabled selected>Pilih Kategori Penilaian</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                @error('kategori')
                    <small class="text-danger">{{ $message }}</small>
                    
                @enderror

                <div class="form-group">
                    <label for="nilai">Nilai</label>
                    <input type="number" name="nilai" class="form-control" required placeholder="1-100"
                        min="1" max="100"
                        oninput="this.value = this.value > 100 ? 100 : (this.value < 1 ? 1 : this.value)">
                    <small>Nilai harus diisi antara 1 sampai 100</small>
                </div>
                @error('nilai')
                    <small class="text-danger">{{ $message }}</small>
                    
                @enderror

                <div class="form-group">
                    <label for="nilai">komentar</label>
                    <textarea name="komentar" class="form-control" required ></textarea>
                    <small>Berikan komentar atau catatan untuk penilaian ini</small>
                </div>
                @error('komentar')
                    <small class="text-danger">{{ $message }}</small>
                    
                @enderror

                <button type="submit" class="btn btn-success mt-3">Simpan Penilaian</button>
            </form>
        </div>
    </div>
</x-layout.main>
