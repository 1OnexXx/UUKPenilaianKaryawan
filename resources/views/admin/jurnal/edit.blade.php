<x-layout.main>
    <x-slot name="title">
        Edit Jurnal
    </x-slot>
    @php
        $role = Auth::user()->role;
    @endphp
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Jurnal</h4>
                <a href="{{ route($role . '.jurnal') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route($role . '.jurnal.update', $jurnal->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($role === 'admin')
                        <div class="form-group">
                            <label for="karyawan">Pilih Karyawan</label>
                            <select name="karyawan_id" id="karyawan" class="form-control" required>
                                <option value="" disabled
                                    {{ old('karyawan_id', $jurnal->karyawan_id) ? '' : 'selected' }}>
                                    Pilih karyawan
                                </option>
                                @foreach ($karyawan as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('karyawan_id', $jurnal->karyawan_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->user->nama_lengkap ?? 'Nama tidak tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul"
                                    placeholder="Contoh: Menyusun laporan keuangan harian..."
                                    value="{{ old('judul', $jurnal->judul) }}">
                                <small class="text-muted">Tuliskan uraian kegiatan harian Anda di sini.</small>
                            </div>
                        </div>
                    </div>
                    @error('judul')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    

                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="uraian">Uraian</label>
                                <textarea class="form-control" id="uraian" name="uraian" rows="4"
                                    placeholder="Contoh: Menyusun laporan keuangan harian...">{{ old('uraian', $jurnal->uraian) }}</textarea>
                                <small class="text-muted">Tuliskan uraian kegiatan harian Anda di sini.</small>
                            </div>
                        </div>
                    </div>
                    @error('uraian')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="form-group mt-3">
                        <label for="lampiran">Lampiran</label>
                        <input type="file" class="form-control" name="lampiran[]" id="lampiran" multiple>
                        <small class="text-muted">Max 5 lampiran (jpg, jpeg, png, pdf, doc, docx, mp4).</small>
                    </div>
                    @error('lampiran')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="komentar">komentar</label>
                                <input type="text" class="form-control" id="komentar" name="komentar"
                                    placeholder="Contoh: Menyusun laporan keuangan harian..."
                                    value="{{ old('komentar', $jurnal->komentar) }}" readonly>
                                <small class="text-muted"> komentar dari penilai </small>
                            </div>
                        </div>
                    </div>
                    @error('komentar')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="komentar_balasan">komentar balasan</label>
                                <input type="text" class="form-control" id="komentar_balasan" name="komentar_balasan"
                                    placeholder="Contoh: Menyusun laporan keuangan harian...">
                                <small class="text-muted">beri komentar untuk lampiran ini </small>
                            </div>
                        </div>
                    </div>
                    @error('komentar_balasan')
                        <small class="text-danger">{{ $message }}</small>
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
