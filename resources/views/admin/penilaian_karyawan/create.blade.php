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
                                    <option value="{{ $item->id }}" data-tipe="{{ $item->tipe_penilaian }}">
                                        {{ $item->nama_kategori }} - {{ ucfirst($item->tipe_penilaian) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="nilai">Nilai</label>
                            <input type="number" name="nilai" id="inputNilai" class="form-control" required
                                placeholder="1-100" min="1" max="100"
                                oninput="this.value = Math.min(100, Math.max(1, this.value))">
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
                        <h5 class="fw-bold">Kriteria Penilaian</h5>
                    
                        <p class="mb-1"><strong>Penilaian Objektif</strong> akan dihitung otomatis berdasarkan data yang tersedia:</p>
                        <ul class="mb-3">
                            <li><strong>Ketepatan/Kesesuaian Waktu:</strong> Dilihat dari laporan kinerja yang dibuat sebelum deadline dari target. Nilai = jumlah laporan tepat waktu ÷ total laporan × 100.</li>
                            <li><strong>Jumlah Laporan:</strong> Dibandingkan dengan target jumlah laporan bulanan dari atasan atau divisi. Nilai = total laporan ÷ target laporan × 100.</li>
                            <li><strong>Jumlah Dokumen:</strong> Dihitung dari lampiran dokumen (pdf/doc/docx) di jurnal yang telah disetujui. Dibandingkan dengan target laporan. Nilai = total dokumen ÷ target × 100.</li>
                        </ul>
                    
                        <p class="mb-1"><strong>Penilaian Subjektif</strong> diberikan secara manual oleh tim penilai berdasarkan kualitas konten dan penyampaian:</p>
                        <ul class="mb-3">
                            <li><strong>Jurnal dan Penulisan:</strong> Harus ditulis dengan jelas, struktur rapi, dan bahasa profesional untuk mencapai nilai ≥ 80.</li>
                            <li><strong>Konten Kegiatan:</strong> Harus menggambarkan aktivitas harian secara detail dan relevan dengan pekerjaan.</li>
                            <li><strong>Jumlah Jurnal:</strong> Minimal 25 jurnal per bulan untuk mencapai nilai standar.</li>
                        </ul>
                    
                        <p class="text-muted">Catatan: Jika kategori penilaian termasuk tipe objektif, nilai akan otomatis terisi dan tidak bisa diedit.</p>
                    </div>
                    

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Jurnal Bulan Ini</h5>
                        <h5 class="mb-0">Disetujui: {{ $jurnal->where('status', 'disetujui')->count() }}</h5>
                    </div>
                    <ul>
                        @forelse ($jurnal->sortByDesc(fn($item) => $item->status === 'dikirim') as $item)
                            <li>
                                <a href="{{ route($role . '.jurnal.show', $item->id) }}?back={{ urlencode(url()->full()) }}">
                                    {{ $item->judul ?? 'Tanpa Judul' }} - {{ $item->created_at->format('d M Y') }} - {{ $item->status }}
                                </a>
                            </li>
                        @empty
                            <li><em>Tidak ada jurnal bulan ini</em></li>
                        @endforelse
                    </ul>

                    <h5 class="mt-4">Laporan Bulanan</h5>
                    <ul>
                        @forelse ($laporan as $item)
                            <li>
                                <a href="{{ route($role . '.laporan.show', $item->id) }}">
                                    Periode: {{ $item->periode }} - Status: {{ ucfirst($item->status) }}
                                </a>
                            </li>
                        @empty
                            <li><em>Tidak ada laporan bulan ini</em></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSelect = document.querySelector('select[name="kategori_id"]');
            const inputNilai = document.querySelector('#inputNilai');
            const karyawanId = document.querySelector('input[name="karyawan_id"]').value;

            kategoriSelect.addEventListener('change', function () {
                const selected = kategoriSelect.options[kategoriSelect.selectedIndex];
                const tipe = selected.getAttribute('data-tipe');

                if (tipe === 'objektif') {
                    fetch(`/penilaian-otomatis?kategori_id=${this.value}&karyawan_id=${karyawanId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.nilai !== undefined) {
                                inputNilai.value = data.nilai;
                                inputNilai.readOnly = true;
                            } else {
                                inputNilai.value = '';
                                inputNilai.readOnly = false;
                                alert('Penilaian otomatis gagal dihitung.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            inputNilai.value = '';
                            inputNilai.readOnly = false;
                            alert('Gagal mengambil data penilaian otomatis.');
                        });
                } else {
                    inputNilai.value = '';
                    inputNilai.readOnly = false;
                }
            });
        });
    </script>
</x-layout.main>
