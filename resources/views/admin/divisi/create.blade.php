<x-layout.main>
    <x-slot name="title">
        Tambah Divisi
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Tambah Divisi</h4>
                <a href="{{ route('admin.divisi') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.divisi.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Nama Divisi</label>
                                <input type="text" class="form-control" id="name" name="nama_divisi" placeholder="Contoh: Keuangan">
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <input type="text" class="form-control" id="description" name="deskripsi" placeholder="Contoh: Mengelola keuangan sekolah">
                                <small class="text-muted">Isikan deskripsi singkat tentang divisi ini.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout.main>
