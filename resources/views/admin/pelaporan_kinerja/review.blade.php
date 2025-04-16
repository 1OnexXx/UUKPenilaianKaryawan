<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Review Pelaporan Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Review Pelaporan Karyawan</h4>
        <span class="badge bg-light text-dark">Periode: {{ $pelaporan->periode }}</span>
      </div>

      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th width="30%">Nama Karyawan</th>
            <td>{{ $pelaporan->karyawan->user->nama_lengkap }}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{{ $pelaporan->karyawan->user->email }}</td>
          </tr>
          <tr>
            <th>Isi Laporan</th>
            <td>{{ $pelaporan->isi_laporan }}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td><span class="badge bg-warning text-dark">{{ ucfirst($pelaporan->status) }}</span></td>
          </tr>
        </table>

        @php
            $role = Auth::user()->role;
        @endphp

        <a href="{{ route($role . '.pelaporan') }}" class="btn btn-secondary mt-3">
          ← Kembali ke Pelaporan Kinerja
        </a>
      </div>
    </div>
  </div>

</body>
</html>
 