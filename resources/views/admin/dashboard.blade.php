<x-layout.main>
    <x-slot name="title">
        Dashboard
    </x-slot>

    <div class="page-title">
        <h3>Dashboard</h3>
        <p class="text-subtitle text-muted">A good dashboard to display your statistics</p>
    </div>
    <section class="section">
        @if (Auth::user()->role != 'karyawan')
            <div class="row mb-2">
                <div class="row">
                    @foreach ($divisi as $item)
                        <div class="col-12 col-md-6">
                            <div class="card card-statistic">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-column">
                                        <!-- Header Card -->
                                        <div class="px-3 py-3 d-flex justify-content-between align-items-center">
                                            <h3 class="card-title" style="font-size: 16px; color: #333;">{{ $item->nama_divisi }}</h3>
                                            <div class="card-right d-flex align-items-center">
                                                <p class="h4 mb-0" style="font-weight: bold; color: #007bff;">{{ $item->karyawan_count }}</p>
                                            </div>
                                        </div>
                
                                        <!-- Deskripsi Divisi -->
                                        <div class="px-3 py-2">
                                            <p>{{ $item->deskripsi }}</p>
                                        </div>
                
                                        <!-- Chart Placeholder (optional, can be added later) -->
                                        <div class="chart-wrapper">
                                            <canvas id="canvas{{ $item->id }}" style="height: 100px !important;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="card card-statistic">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class='card-title' style="font-size: 16px;">Rata-rata Nilai Karyawan</h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p>{{ $data['rata_rata_nilai'] }}</p>
                                    </div>
                                </div>
                                <div class="chart-wrapper">
                                    <canvas id="canvas2" style="height:100px !important"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card-statistic">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class='card-title' style="font-size: 16px;">Jumlah Penilaian Bulan Ini</h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p>{{ $data['jumlah_penilaian_bulan_ini'] }}</p>
                                    </div>
                                </div>
                                <div class="chart-wrapper">
                                    <canvas id="canvas3" style="height:100px !important"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card-statistic">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class='card-title' style="font-size: 19px;">Penilaian Hari Ini</h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p>{{ $data['jumlah_penilaian_hari_ini'] }}</p>
                                    </div>
                                </div>
                                <div class="chart-wrapper">
                                    <canvas id="canvas4" style="height:100px !important"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class='card-heading p-1 pl-3'>Rata-Rata Nilai Karyawan per Bulan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="pl-3">
                                    <h1 class='mt-5'>nilai tertinggi {{ $nilaiTertinggi }}</h1>
                                    <p class="text-xs">
                                        <span
                                            class="{{ $kenaikan > 0 ? 'text-green' : ($kenaikan < 0 ? 'text-red' : 'text-gray') }}">
                                            <i data-feather="bar-chart" width="15"></i>
                                            @if ($kenaikan > 0)
                                                +{{ number_format($kenaikan, 2) }}%
                                            @elseif($kenaikan < 0)
                                                {{ number_format($kenaikan, 2) }}%
                                            @else
                                                0%
                                            @endif
                                        </span> Bulan lalu
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8 col-12">
                                <canvas id="bar"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                {{-- <div class="card ">
                    <div class="card-header">
                        <h4>Your Earnings</h4>
                    </div>
                    <div class="card-body">
                        <div id="radialBars"></div>
                        <div class="text-center mb-5">
                            <h6>From last month</h6>
                            <h1 class='text-green'>+$2,134</h1>
                        </div>
                    </div>
                </div> --}}
                <div class="card widget-todo">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="card-title d-flex">
                            <i class='bx bx-check font-medium-5 pl-25 pr-75'></i>Top karyawan
                        </h4>

                    </div>
                    <div class="card-body px-0 py-1">
                        <table class='table table-borderless'>
                            @foreach ($topKaryawan as $index => $item)
                                @php
                                    $warnaList = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info'];
                                    $warna = $warnaList[array_rand($warnaList)];
                                @endphp

                                <tr>
                                    <td class='col-3'>
                                        {{ $item->karyawan->user->nama_lengkap ?? '-' }}
                                    </td>
                                    <td class='col-6'>
                                        <div class="progress">
                                            <div class="progress-bar {{ $warna }}" role="progressbar"
                                                style="width: {{ min($item->rata_rata, 100) }}%;"
                                                aria-valuenow="{{ $item->rata_rata }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ number_format($item->rata_rata, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class='col-3 text-center'>
                                        {{ number_format($item->rata_rata, 2) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('bar').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Rata-Rata Nilai Karyawan',
                    data: @json($nilaiChart),
                    backgroundColor: Array.from({
                        length: 12
                    }, () => {
                        // Random 5 warna
                        const colors = ['#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];
                        return colors[Math.floor(Math.random() * colors.length)];
                    }),
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>

</x-layout.main>
