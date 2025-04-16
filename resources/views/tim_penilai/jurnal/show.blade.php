{{-- contoh show.blade.php untuk jurnal --}}
<x-layout.main>
    <x-slot name="title">Detail Jurnal</x-slot>
    @php
    $role = Auth::user()->role;
@endphp
    <p>{{ $jurnal->uraian }}</p>
    <p><strong>Oleh:</strong> {{ $jurnal->karyawan->user->nama_lengkap }}</p>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">← Kembali</a>


</x-layout.main>
