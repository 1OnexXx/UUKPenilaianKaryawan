<x-layout.main>
    <x-slot name="title">
        Edit Kategori Penilaian
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Kategori Penilaian</h4>
                <a href="{{ route('admin.kategori_penilaian') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.kategori_penilaian.update' , $kate->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Nama Divisi</label>
                                <input type="text" class="form-control" id="name" name="nama_kategori" placeholder="Contoh: Keuangan" value="{{ old('nama_kategori', $kate->nama_kategori) }}">
                                @error('nama_kategori')
                                    <div class="alert alert-danger mt-2" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <input type="text" class="form-control" id="description" name="deskripsi" placeholder="Contoh: Mengelola keuangan sekolah" value="{{old('deskripsi' , $kate->deskripsi)}}">
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
