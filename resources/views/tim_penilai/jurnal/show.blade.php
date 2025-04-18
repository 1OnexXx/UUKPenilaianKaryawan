    <x-layout.main>
        <x-slot name="title">Detail Jurnal</x-slot>

        @php
            $role = Auth::user()->role;
        @endphp

        <p>{{ $jurnal->uraian }}</p>
        <p><strong>Oleh:</strong> {{ $jurnal->karyawan->user->nama_lengkap }}</p>

        <!-- Menampilkan Lampiran jika ada -->
        <div class="mt-4">
            <h5>Lampiran:</h5>
            @forelse ($jurnal->lampiran as $item)
                <div class="mb-3">

                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Lihat Lampiran: {{ basename($item->file_path) }}
                    </a>

                </div>
            @empty
                <p class="text-muted">Tidak ada lampiran untuk jurnal ini.</p>
            @endforelse
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route($role . '.jurnal.update', $jurnal->id) }}" method="post">
            @csrf
            @method('PUT')
            @if (!empty($jurnal->komentar))
                <div class="form-group mt-3">
                    <label for="komentar_balasan">Balasan Komentar</label>
                    <input type="text" name="komentar_balasan" id="komentar_balasan" class="form-control"
                        value="{{ old('komentar_balasan', $jurnal->komentar_balasan) }}" readonly>
                </div>
            @endif
            <div class="form-group mt-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="disetujui" {{ $jurnal->status == 0 ? 'selected' : '' }}>disetujui</option>
                    <option value="ditolak" {{ $jurnal->status == 1 ? 'selected' : '' }}>ditolak</option>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="komentar">komentar</label>
                <input type="text" name="komentar" id="komentar" class="form-control"></input>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        <a href="{{ session('previous_url', url('/')) }}" class="btn btn-secondary mt-3">← Kembali</a>



    </x-layout.main>
