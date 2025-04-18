<x-layout.main>
    <x-slot name="title">Form Penilaian</x-slot>

    <div class="card">
        <div class="card-header">
            Form Penilaian untuk: <strong>{{ $karyawan->user->nama_lengkap }}</strong>
        </div>
        @php
            $role = Auth::user()->role;
        @endphp

        <div class="card-body">
            <div class="row">
                <!-- Kolom kiri: Form Penilaian -->
                <div class="col-md-6 border-end">
                    <form action="{{ route('tim_penilai.riwayat_penilaian.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">

                        <div class="form-group mb-3">
                            <label for="kategori">Kategori Penilaian</label>
                            <select name="kategori_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Kategori Penilaian</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="nilai">Nilai</label>
                            <input type="number" name="nilai" class="form-control" required placeholder="1-100"
                                min="1" max="100"
                                oninput="this.value = this.value > 100 ? 100 : (this.value < 1 ? 1 : this.value)">
                            <small>Nilai harus diisi antara 1 sampai 100</small>
                            @error('nilai')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="komentar">Komentar</label>
                            <textarea name="komentar" class="form-control" required></textarea>
                            <small>Berikan komentar atau catatan untuk penilaian ini</small>
                            @error('komentar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Simpan Penilaian</button>
                    </form>
                </div>

                <!-- Kolom kanan: Jurnal dan Laporan -->
                <div class="col-md-6 ps-4">
                    <div class="mb-4">
                        <h5 class="fw-bold">Kriteria Penulisan Laporan Kinerja</h5>
                        <ul>
                            <li><strong>Jurnal dan Penulisan:</strong> Untuk mendapatkan nilai 80, penulisan harus jelas
                                dan terstruktur dengan baik.</li>
                            <li><strong>Konten Kegiatan:</strong> Kegiatan yang telah dilakukan harus dijelaskan dengan
                                rinci.</li>
                            <li><strong>Jumlah Jurnal:</strong> 25 jurnal minimum diperlukan untuk mencapai nilai
                                standar.</li>
                        </ul>
                        <p class="text-muted">Untuk mencapai nilai standar dalam laporan kinerja, penulisan harus
                            mencakup hal-hal di atas secara detail.</p>
                    </div>
                    <h5>Jurnal Bulan Ini</h5>
                    <ul>
                        @forelse ($jurnal->sortByDesc(fn($item) => $item->status === 'dikirim') as $item)
                            <li>
                                <a
                                    href="{{ route($role . '.jurnal.show', $item->id) }}?back={{ urlencode(url()->full()) }}">
                                    {{ $item->judul ?? 'Tanpa Judul' }} - {{ $item->created_at->format('d M Y') }} -
                                    {{ $item->status }}
                                </a>
                            </li>
                        @empty
                            <li><em>Tidak ada jurnal bulan ini</em></li>
                        @endforelse
                    </ul>

                    <h5 class="mt-4">Laporan Bulanan </h5>
                    <ul>
                        @forelse ($laporan as $item)
                            <li><a href="{{ route($role . '.laporan.show', $item->id) }}">Periode:
                                    {{ $item->periode }} - Status: {{ ucfirst($item->status) }}</a></li>
                        @empty
                            <li><em>Tidak ada laporan bulan ini</em></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layout.main>
