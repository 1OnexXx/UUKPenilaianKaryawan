<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penilaian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Laporan Penilaian Karyawan</h2>
<h3>Jenis Laporan: {{ ucfirst(request()->jenis_laporan) }}</h3>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>Nama Karyawan</th>
            <th>Email</th>
            <th>Jenis Laporan</th>
            <th>Rekomendasi</th>
            <th>Periode</th>
            <th>Dicatat Oleh</th>
            <th>Rata-Rata Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporan as $index => $lap)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $lap->karyawan->user->nama_lengkap }}</td>
                <td>{{ $lap->karyawan->user->email }}</td>
                <td>{{ ucfirst($lap->jenis_laporan) }}</td>
                <td>{{ $lap->rekomendasi }}</td>
                <td>{{ $lap->periode }}</td>
                <td>{{ $lap->dibuat_oleh ? App\Models\User::find($lap->dibuat_oleh)->nama_lengkap : 'Admin' }}</td>
                <td>{{ $lap->rata_rata_nilai }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
