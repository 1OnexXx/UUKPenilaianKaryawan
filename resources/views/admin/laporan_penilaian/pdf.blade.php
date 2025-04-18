<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian {{ ucfirst($jenis_laporan) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .divisi-title {
            margin-top: 40px;
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            width: 100%;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- Kop Surat Sekolah --}}
    <table style="width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
        <tr>
            <td style="width: 20%; text-align: center;">
                <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo Sekolah" style="width: 80px;">
            </td>
            <td style="width: 60%; text-align: center;">
                <h1 style="margin: 0; font-size: 18px;">LAPORAN PENILAIAN KARYAWAN</h1>
                <p style="margin: 5px 0;">Jenis Laporan: {{ ucfirst($jenis_laporan) }}</p>
                <p style="margin: 5px 0;">Tanggal Cetak: {{ now()->format('d F Y') }}</p>
            </td>
            <td style="width: 20%; text-align: center;">
                <img src="{{ asset('images/logo_rpl.png') }}" alt="Logo RPL" style="width: 80px;">
            </td>
        </tr>
    </table>

    <input type="hidden" hidden>{{ $divisiid }}</input>


    @php
        $laporanByDivisi = $laporan->groupBy('karyawan.divisi.nama_divisi');
    @endphp

    @forelse ($laporanByDivisi as $namaDivisi => $dataDivisi)
        <div class="divisi-title">Divisi: {{ $namaDivisi ?? 'Tanpa Divisi' }}</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Penilai</th>
                    <th>Kategori</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalNilai = 0;
                    $jumlahPenilaian = 0;
                @endphp

                @foreach ($dataDivisi as $index => $data)
                    @php
                        $nilaiKaryawan = $data->karyawan->penilaian_karyawan;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data->karyawan->user->nama_lengkap ?? '-' }}</td>
                        <td>
                            @foreach ($nilaiKaryawan as $penilaian)
                                {{ $penilaian->penilai->nama_lengkap ?? '-' }}<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($nilaiKaryawan as $penilaian)
                                {{ $penilaian->kategori->nama_kategori ?? '-' }}<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($nilaiKaryawan as $penilaian)
                                {{ $penilaian->nilai ?? '-' }}<br>
                                @php
                                    if ($penilaian->nilai) {
                                        $totalNilai += $penilaian->nilai;
                                        $jumlahPenilaian++;
                                    }
                                @endphp
                            @endforeach
                        </td>
                        <td>
                            @foreach ($nilaiKaryawan as $penilaian)
                                {{ $penilaian->komentar ?? '-' }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach

                {{-- Rata-rata dan status --}}
                @php
                    $rata2 = $jumlahPenilaian > 0 ? $totalNilai / $jumlahPenilaian : 0;
                    $status = $rata2 >= 80 ? 'Baik' : 'Harus Ditingkatkan';
                @endphp
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Rata-rata Nilai</td>
                    <td colspan="2" style="text-align: center; font-weight: bold;">
                        {{ number_format($rata2, 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Status</td>
                    <td colspan="2" style="text-align: center; font-weight: bold;">
                        {{ $status }}
                    </td>
                </tr>
            </tbody>
        </table>
    @empty
        <p style="text-align: center;">Data tidak tersedia</p>
    @endforelse

    {{-- Footer --}}
    <div class="footer">
        Dicetak oleh sistem pada {{ now()->format('d-m-Y H:i') }}
    </div>

    {{-- Preview Laporan --}}
    <h3>Preview Laporan Penilaian ({{ ucfirst($jenis_laporan) }})</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Rata-rata Nilai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataKaryawan as $karyawan)
                <tr>
                    <td>{{ $karyawan['nama'] }}</td>
                    <td>{{ $karyawan['divisi'] }}</td>
                    <td>{{ number_format($karyawan['rata_rata'], 2) }}</td>
                    <td>{{ $karyawan['status'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <button onclick="window.print()" class="btn btn-info mt-3">
        <i class="bi bi-printer"></i> Print Laporan
    </button>
    <button onclick="history.back()" class="btn btn-secondary mt-3" style="margin-left: 10px;">
        ⬅ Kembali
    </button>
    

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</html>
