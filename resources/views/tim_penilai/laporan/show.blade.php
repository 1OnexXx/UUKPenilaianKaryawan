<x-layout.main>
    <x-slot name="title">Detail Laporan</x-slot>
@php
    $role = Auth::user()->role;
@endphp
    <h4>Periode: {{ $laporan->periode }}</h4>
    <p>{{ $laporan->isi_laporan }}</p>
    <p><strong>Status:</strong> {{ ucfirst($laporan->status) }}</p>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">← Kembali</a>

</x-layout.main>
