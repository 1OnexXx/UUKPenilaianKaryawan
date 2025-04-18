<x-layout.main>
    <x-slot name="title">
        Tambah Target Kinerja
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Tambah Target Kinerja</h4>
                <a href="{{ route('admin.penugasan') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.penugasan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="karyawan_id">Pilih Karyawan</label>
                                <select name="karyawan_id" id="karyawan_id" class="form-control">
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach ($karyawan as $karyawans)
                                    <option value="{{ $karyawans->id }}">{{ $karyawans->user->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small>pilih jika ingin ingin spesifik karyawan mana yg diberi tugas kosongkan jika ingin
                                semua karyawan</small>

                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="divisi_id">Pilih Divisi</label>
                                <select name="divisi_id" id="divisi_id" class="form-control">
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach ($divisi as $divisis)
                                        <option value="{{ $divisis->id }}">{{ $divisis->nama_divisi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small>pilih jika ingin ingin spesifik divisi mana yg diberi tugas kosongkan jika ingin
                                semua divisi</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <input type="text" name="periode" id="periode" class="form-control"
                                    placeholder="Contoh: Q1 2025">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="judul_target">Judul Target</label>
                                <input type="text" name="judul_target" id="judul_target" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="target_laporan">Target Laporan</label>
                                <input type="number" name="target_laporan" id="target_laporan" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="deadline">Deadline</label>
                                <input type="date" name="deadline" id="deadline" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nama_pembuat">Dibuat Oleh</label>
                                <input type="text" id="nama_pembuat" class="form-control"
                                    value="{{ auth()->user()->nama_lengkap }}" readonly>
                                <input type="hidden" name="dibuat_oleh" value="{{ auth()->user()->id }}">
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
