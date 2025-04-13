<x-layout.main>
    <x-slot name="title">
        Edit Jurnal
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Jurnal</h4>
                <a href="{{ route('karyawan.jurnal') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('karyawan.jurnal.update' , $jurnal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="uraian">Uraian</label>
                                <textarea class="form-control" id="uraian" name="uraian" rows="4" placeholder="Contoh: Menyusun laporan keuangan harian...">{{ old('uraian' , $jurnal->uraian) }}</textarea>
                                <small class="text-muted">Tuliskan uraian kegiatan harian Anda di sini.</small>
                            </div>
                        </div>
                    </div>
                    @error('uraian')
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
