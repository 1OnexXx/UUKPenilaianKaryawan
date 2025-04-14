<x-layout.main>
    <x-slot name="title">
        Edit Jurnal
    </x-slot>
    @php
        $role = Auth::user()->role;
    @endphp
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edit Jurnal</h4>
                <a href="{{ route($role . '.jurnal') }}" class="btn btn-sm btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route($role . '.jurnal.update', $jurnal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if ($role === 'admin')
                        <div class="form-group">
                            <label for="karyawan">Pilih Karyawan</label>
                            <select name="karyawan_id" id="karyawan" class="form-control" required>
                                <option value="" disabled
                                    {{ old('karyawan_id', $jurnal->karyawan_id) ? '' : 'selected' }}>
                                    Pilih karyawan
                                </option>
                                @foreach ($karyawan as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('karyawan_id', $jurnal->karyawan_id) == $item->id ? 'selected' : '' }} disabled>
                                        {{ $item->user->nama_lengkap ?? 'Nama tidak tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif



                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="uraian">Uraian</label>
                                <textarea class="form-control" id="uraian" name="uraian" rows="4"
                                    placeholder="Contoh: Menyusun laporan keuangan harian...">{{ old('uraian', $jurnal->uraian) }}</textarea>
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
