<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            font-size: 14px;
        }
        h2, h3 {
            text-align: center;
        }
        .section {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #000;
        }
        .gray {
            background-color: #eee;
        }
    </style>
</head>
<body>

<h2>Laporan Penilaian Karyawan</h2>
<h3>Jenis: {{ ucfirst(request()->jenis_laporan) }} | Periode: {{ $laporan->first()->periode ?? '-' }}</h3>

@foreach($laporan as $lap)
<div class="section">
    {{-- Bagian Identitas Karyawan --}}
    <h4>Identitas Karyawan</h4>
    <table>
        <tr><th>Nama Lengkap</th><td>{{ $lap->karyawan->user->nama_lengkap }}</td></tr>
        <tr><th>Email</th><td>{{ $lap->karyawan->user->email }}</td></tr>
        <tr><th>NIP</th><td>{{ $lap->karyawan->nip }}</td></tr>
        <tr><th>Divisi</th><td>{{ $lap->karyawan->divisi->nama_divisi ?? '-' }}</td></tr>
        <tr><th>Jabatan</th><td>{{ $lap->karyawan->jabatan }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($lap->karyawan->status) }}</td></tr>
    </table>

    {{-- Bagian Penilaian --}}
    <h4>Detail Penilaian per Kategori</h4>
    <table>
        <thead class="gray">
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nilai</th>
                <th>Komentar</th>
                <th>Penilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lap->karyawan->penilaian->where('periode', $lap->periode) as $index => $nilai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nilai->kategori->nama_kategori }}</td>
                    <td>{{ $nilai->nilai }}</td>
                    <td>{{ $nilai->komentar ?? '-' }}</td>
                    <td>{{ $nilai->penilai->nama_lengkap }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Bagian Jurnal Harian --}}
    <h4>Jurnal Harian</h4>
    <table>
        <thead class="gray">
            <tr>
                <th>Tanggal</th>
                <th>Uraian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lap->karyawan->jurnalHarian as $jurnal)
                <tr>
                    <td>{{ $jurnal->tanggal }}</td>
                    <td>{{ $jurnal->uraian }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Laporan Kinerja --}}
    <h4>Pelaporan Kinerja</h4>
    <table>
        <thead class="gray">
            <tr>
                <th>Periode</th>
                <th>Isi Laporan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lap->karyawan->laporanKinerja->where('periode', $lap->periode) as $kinerja)
                <tr>
                    <td>{{ $kinerja->periode }}</td>
                    <td>{{ $kinerja->isi_laporan }}</td>
                    <td>{{ ucfirst($kinerja->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Rangkuman Nilai --}}
    <h4>Rangkuman Penilaian</h4>
    <table>
        <tr><th>Rata-Rata Nilai</th><td>{{ $lap->rata_rata_nilai }}</td></tr>
        <tr><th>Rekomendasi</th><td>{{ $lap->rekomendasi ?? '-' }}</td></tr>
        <tr><th>Dicatat Oleh</th><td>{{ $lap->dibuat_oleh ? \App\Models\User::find($lap->dibuat_oleh)->nama_lengkap : 'Admin' }}</td></tr>
    </table>
</div>
<hr style="margin: 50px 0;">
@endforeach

</body>
</html>
