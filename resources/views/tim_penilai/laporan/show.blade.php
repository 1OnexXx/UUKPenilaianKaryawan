<x-layout.main>
    <x-slot name="title">Detail Laporan</x-slot>

    @php
        $role = Auth::user()->role;
    @endphp

    <!-- Menampilkan Periode dan Isi Laporan -->
    <h4>Periode: {{ $laporan->periode }}</h4>
    <p>{{ $laporan->isi_laporan }}</p>
    <p><strong>Status:</strong> {{ ucfirst($laporan->status) }}</p>

    <!-- Menampilkan Lampiran jika ada -->
    <div class="mt-4">
        <h5>Lampiran:</h5>
        @forelse ($laporan->lampiran2 as $item)
            <div class="mb-3">
                @php
                    $fileExtension = pathinfo($item->file_path, PATHINFO_EXTENSION);
                @endphp

                <!-- Cek jenis file dan tampilkan sesuai tipe -->
                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Lihat Gambar: {{ basename($item->file_path) }}
                    </a>
                @elseif (in_array($fileExtension, ['pdf']))
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Lihat PDF: {{ basename($item->file_path) }}
                    </a>
                @elseif (in_array($fileExtension, ['mp4']))
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Lihat Video: {{ basename($item->file_path) }}
                    </a>
                @else
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Lihat Lampiran: {{ basename($item->file_path) }}
                    </a>
                @endif
            </div>
        @empty
            <p class="text-muted">Tidak ada lampiran untuk laporan ini.</p>
        @endforelse
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">← Kembali</a>
</x-layout.main>
