<x-layout.main>
    <x-slot name="title">
        Edit Pelaporan Kinerja
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Pelaporan Kinerja</h4>
                <a href="{{ route('karyawan.pelaporan') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card-body">
                <form action="{{ route('karyawan.pelaporan.update' , $pelaporan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="isi_laporan">isi laporan bulanan</label>
                                <textarea class="form-control" id="isi_laporan" name="isi_laporan" rows="4" placeholder="Contoh: Menyusun laporan keuangan Bulanan...">{{ old('isi_laporan' , $pelaporan->isi_laporan) }}</textarea>
                                <small class="text-muted mt-2">Catatan: Pastikan Anda mengisi pelaporan dengan detail kegiatan yang telah dilakukan selama sebulan terakhir.</small>
                            </div>
                        </div>
                    </div>
                    @error('isi_laporan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <label>Lampiran (max 5)</label>
                    <input type="file" name="lampiran[]" multiple accept=".jpg,.png,.pdf,.doc,.docx,.mp4">



                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout.main>
