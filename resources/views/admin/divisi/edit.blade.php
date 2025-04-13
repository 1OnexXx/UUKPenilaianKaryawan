<x-layout.main>
    <x-slot name="title">
        Edit Divisi
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Divisi</h4>
                <a href="{{ route('admin.divisi') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.divisi.update' , $divisi->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Nama Divisi</label>
                                <input type="text" class="form-control" id="name" name="nama_divisi" placeholder="Contoh: Keuangan" value="{{ old('nama_divisi', $divisi->nama_divisi) }}">
                                @error('nama_divisi')
                                    <div class="alert alert-danger mt-2" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <input type="text" class="form-control" id="description" name="deskripsi" placeholder="Contoh: Mengelola keuangan sekolah" value="{{old('deskripsi' , $divisi->deskripsi)}}">
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
