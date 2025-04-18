<x-layout.main>
    <x-slot name="title">
        Edit Target Kinerja
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Target Kinerja</h4>
                <a href="{{ route('admin.penugasan') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card-body">
                <form action="{{ route('admin.penugasan.update', $penugasan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="karyawan_id">Pilih Karyawan</label>
                                <select name="karyawan_id" id="karyawan_id" class="form-control">
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach ($karyawan as $karyawans)
                                        <option value="{{ $karyawans->id }}" {{ $penugasan->karyawan_id == $karyawans->user_id ? 'selected' : '' }}>
                                            {{ $karyawans->user->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small>Pilih jika ingin spesifik karyawan mana yang diberi tugas, kosongkan jika ingin semua karyawan</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="divisi_id">Pilih Divisi</label>
                                <select name="divisi_id" id="divisi_id" class="form-control">
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach ($divisi as $divisis)
                                        <option value="{{ $divisis->id }}" {{ $penugasan->divisi_id == $divisis->id ? 'selected' : '' }}>
                                            {{ $divisis->nama_divisi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small>Pilih jika ingin spesifik divisi mana yang diberi tugas, kosongkan jika ingin semua divisi</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <input type="text" name="periode" id="periode" class="form-control"
                                    value="{{ $penugasan->periode }}" placeholder="Contoh: Q1 2025">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="judul_target">Judul Target</label>
                                <input type="text" name="judul_target" id="judul_target" class="form-control"
                                    value="{{ $penugasan->judul_target }}">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="target_laporan">Target Laporan</label>
                                <input type="number" name="target_laporan" id="target_laporan" class="form-control"
                                    value="{{ $penugasan->target_laporan }}">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="deadline">Deadline</label>
                                <input type="date" name="deadline" id="deadline" class="form-control"
                                    value="{{ $penugasan->deadline }}">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nama_pembuat">Dibuat Oleh</label>
                                <input type="text" id="nama_pembuat" class="form-control"
                                    value="{{ $penugasan->dibuatOleh->nama_lengkap }}" readonly>
                                <input type="hidden" name="dibuat_oleh" value="{{ $penugasan->dibuat_oleh }}">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.penugasan') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout.main>
