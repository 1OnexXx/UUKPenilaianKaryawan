<style>
    .card {
        max-width: 420px;
        margin: 30px auto;
        background: linear-gradient(to bottom right, #ffffff, #f4f6f9);
        border: 1px solid #ddd;
        border-radius: 16px;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .card-header {
        background-color: #0066cc;
        padding: 22px;
        color: white;
        text-align: center;
        position: relative;
    }

    .card-header h2 {
        margin: 0;
        font-size: 24px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .photo-wrapper {
        text-align: center;
        margin-top: -50px;
        margin-bottom: 15px;
    }

    .photo {
        width: 110px;
        height: 110px;
        border: 4px solid white;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }

    .photo:hover {
        transform: scale(1.05);
    }

    .card-content {
        padding: 22px;
        font-size: 15px;
        color: #333;
    }

    .card-content p {
        margin: 8px 0;
        line-height: 1.5;
    }

    .card-content strong {
        display: inline-block;
        width: 140px;
        color: #555;
    }

    .status {
        padding: 4px 12px;
        color: #fff;
        border-radius: 50px;
        font-weight: bold;
        font-size: 12px;
        display: inline-block;
        text-transform: capitalize;
    }

    .status.aktif {
        background-color: #28a745;
    }

    .status.non-aktif {
        background-color: #dc3545;
    }

    .footer {
        background-color: #f1f1f1;
        padding: 12px;
        text-align: center;
        font-size: 13px;
        color: #666;
        border-top: 1px solid #ddd;
    }

    .btn-primary {
        display: inline-block;
        margin: 20px auto 10px auto;
        padding: 10px 18px;
        background-color: #0066cc;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0055a3;
    }
</style>

<div style="text-align: center;">
    <a href="{{ route('admin.karyawan') }}" class="btn btn-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Kartu Tanda Karyawan</h2>
    </div>

    <div class="photo-wrapper">
        <img class="photo" src="{{ asset('assets/images/dubmyUKK.jpg') }}" alt="Foto Karyawan Dummy">
    </div>

    <div class="card-content">
        <p><strong>Nama Lengkap:</strong> {{ $karyawan->user->nama_lengkap }}</p>
        <p><strong>Email:</strong> {{ $karyawan->user->email }}</p>
        <p><strong>Divisi:</strong> {{ $karyawan->divisi->nama_divisi }}</p>
        <p><strong>NIP:</strong> {{ $karyawan->nip }}</p>
        <p><strong>Jabatan:</strong> {{ $karyawan->jabatan }}</p>
        <p><strong>No HP:</strong> {{ $karyawan->no_hp }}</p>
        <p><strong>Tanggal Masuk:</strong> {{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d M Y') }}</p>
        <p><strong>Status:</strong>
            <span class="status {{ $karyawan->status == 'aktif' ? 'aktif' : 'non-aktif' }}">
                {{ ucfirst($karyawan->status) }}
            </span>
        </p>
    </div>

    <div class="footer">
        ID: {{ $karyawan->id }} | Dibuat pada: {{ $karyawan->created_at->format('d M Y') }}
    </div>
</div>
