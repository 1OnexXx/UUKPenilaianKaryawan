<x-layout.main>
    <x-slot name="title">Edit Penilaian</x-slot>

    <div class="card">
        <div class="card-header">
            Edit Penilaian untuk: <strong>{{ $penilaian->karyawan->user->nama_lengkap }}</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('tim_penilai.riwayat_penilaian.update', $penilaian->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- <input type="hidden" name="karyawan_id" value="{{ $penilaian->karyawan->user->id }}"> --}}
                <input type="hidden" name="karyawan_id" value="{{ $penilaian->karyawan->id }}">


                <div class="form-group">
                    <label for="kategori">Kategori Penilaian</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="" disabled>Pilih Kategori Penilaian</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $penilaian->kategori_id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nilai">Nilai</label>
                    <input type="number" name="nilai" class="form-control" required placeholder="1-100"
                        min="1" max="100"
                        value="{{ old('nilai', $penilaian->nilai) }}"
                        oninput="this.value = this.value > 100 ? 100 : (this.value < 1 ? 1 : this.value)">
                    <small>Nilai harus diisi antara 1 sampai 100</small>
                    @error('nilai')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="komentar">Komentar</label>
                    <textarea name="komentar" class="form-control" required>{{ old('komentar', $penilaian->komentar) }}</textarea>
                    <small>Berikan komentar atau catatan untuk penilaian ini</small>
                    @error('komentar')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Penilaian</button>
            </form>
        </div>
    </div>
</x-layout.main>
