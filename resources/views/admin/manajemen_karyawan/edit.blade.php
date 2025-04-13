<x-layout.main>
    <x-slot name="title">
        Edit Kategori Penilaian
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Kategori Penilaian</h4>
                <a href="{{ route('admin.karyawan') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.karyawan.update' , $karyawan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="divisi_id">Berada di Divisi</label>
                                <select class="form-control" id="divisi_id" name="divisi_id">
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach ($divisi as $d)
                                        <option value="{{ $d->id }}" {{ old('divisi_id', $karyawan->divisi_id) == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama_divisi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('divisi_id')
                                    <div class="alert alert-danger mt-2" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="description">nip</label>
                                <input type="number" class="form-control" id="nip" name="nip" placeholder="Contoh: 9855543 " value="{{old('nip' , $karyawan->nip)}}">
                            </div>
                        </div>


                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="description">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Contoh: staff Keuangan" value="{{old('jabatan' , $karyawan->jabatan)}}">
                            </div>
                        </div>


                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="description">no hp</label>
                                <input type="number" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 0984588723" value="{{old('np_hp' , $karyawan->no_hp)}}">
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="description">tanggal masuk</label>
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" placeholder="Contoh: 0984588723" value="{{old('tanggal_masuk' , $karyawan->tanggal_masuk)}}">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="aktif" {{ old('status', $karyawan->status) == 'aktif' ? 'selected' : '' }}>aktif</option>
                                    <option value="non-aktif" {{ old('status', $karyawan->status) == 'non-aktif' ? 'selected' : '' }}>non-aktif</option>
                                </select>
                                @error('status')
                                    <div class="alert alert-danger mt-2" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
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
