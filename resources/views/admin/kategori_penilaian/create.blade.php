<x-layout.main>
    <x-slot name="title">
        Tambah Kategori Penilaian
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Tambah Kategori Penilaian</h4>
                <a href="{{ route('admin.divisi') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.kategori_penilaian.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Nama Kategori</label>
                                <input type="text" class="form-control" id="name" name="nama_kategori" placeholder="Contoh: Keuangan">
                            </div>
                        </div>

                        <div>
                            <div class="form-group"></div>
                                <label for="tipe_penilaian">Penilaian</label>
                                <select name="tipe_penilaian" id="tipe_penilaian" class="form-control" required>
                                    <option value="">== Pilih Tipe Penilaian ==</option>
                                    <option value="subjektif">subjektif</option>
                                    <option value="obektif">obektif</option>
                                </select>
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
