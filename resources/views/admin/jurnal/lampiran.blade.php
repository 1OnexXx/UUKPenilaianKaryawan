<x-layout.main>
    <x-slot name="title">
        Lampiran untuk Jurnal: {{ $jurnal->uraian }}
    </x-slot>

    <section class="section">
        <div class="section-header">
            <h1>Lampiran untuk Jurnal: {{ $jurnal->uraian }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @forelse ($lampiran as $item)
                        <div class="mb-3">

                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Lihat Lampiran: {{ basename($item->file_path) }}
                            </a>
                            

                        </div>
                    @empty
                        <p class="text-muted">Tidak ada lampiran untuk jurnal ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-layout.main>
