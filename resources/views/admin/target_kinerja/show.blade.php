<x-layout.main>
    <x-slot name="title">
        Detail Target Kinerja
    </x-slot>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Detail Target Kinerja</h4>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                    <p>{{ $target->karyawan->user->nama_lengkap ?? 'Semua Karyawan' }}</p>
                </div>
                <div class="mb-3">
                    <label for="nama_divisi" class="form-label">Nama Divisi</label>
                    <p>{{ $target->divisi->nama_divisi ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <label for="judul_target" class="form-label">Judul Target</label>
                    <p>{{ $target->judul_target }}</p>
                </div>
                <div class="mb-3">
                    <label for="target_laporan" class="form-label">Target Laporan</label>
                    <p>{{ $target->target_laporan }}</p>
                </div>
                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline</label>
                    <p>{{ $target->deadline }}</p>
                </div>
                <div class="mb-3">
                    <label for="dibuat_oleh" class="form-label">Di buat oleh</label>
                    <p>{{ $target->dibuatOleh->nama_lengkap }}</p>
                </div>
                <div class="mb-3">
                    <label for="periode" class="form-label">Periode</label>
                    <p>{{ $target->periode }}</p>
                </div>
                <a href="{{ route('karyawan.penugasan') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</x-layout.main>
