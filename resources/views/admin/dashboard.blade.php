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
            <div x-data="{ openModal: false }">
                <div class="row mb-2">
                    <div class="col-12 col-md-3 mb-3">
                        <div class="card card-statistic">
                            <div class="card-body p-0">
                                <div class="d-flex flex-column" @click="openModal = !openModal">
                                    <div class='px-3 py-3 d-flex justify-content-between'>
                                        <h3 class='card-title' style="font-size: 16px;">Jumlah Karyawan</h3>
                                        <div class="card-right d-flex align-items-center">
                                            <p>{{ $total_karyawan }}</p>
                                        </div>
                                    </div>
                                    <div class="chart-wrapper">
                                        <canvas id="canvas2" style="height:100px !important"></canvas>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-12 col-md-3 mb-3">
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
                                        <canvas id="canvas3" style="height:100px !important"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
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
                                        <canvas id="canvas4" style="height:100px !important"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <div class="card card-statistic">
                            <div class="card-body p-0">
                                <div class="d-flex flex-column">
                                    <div class='px-3 py-3 d-flex justify-content-between'>
                                        <h3 class='card-title' style="font-size: 16px;">Penilaian Hari Ini</h3>
                                        <div class="card-right d-flex align-items-center">
                                            <p>{{ $data['jumlah_penilaian_hari_ini'] }}</p>
                                        </div>
                                    </div>
                                    <div class="chart-wrapper">
                                        <canvas id="canvas5" style="height:100px !important"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div x-show="openModal" x-transition
                    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
                    <div class="bg-white p-6 rounded-lg w-full max-w-6xl overflow-y-auto max-h-[90vh]">
                        <h3 class="text-xl font-semibold mb-4">Karyawan Berdasarkan Divisi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($divisiList as $divisi)
                                <div class="mb-6">
                                    <h4 class="font-bold text-lg mb-2">{{ $divisi }}</h4>
                                    @php
                                        $filteredKaryawan = $karyawan->filter(
                                            fn($kar) => $kar->detail &&
                                                $kar->detail->divisi &&
                                                $kar->detail->divisi->nama_divisi === $divisi,
                                        );
                                    @endphp
                                    @if ($filteredKaryawan->isNotEmpty())
                                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                            @foreach ($filteredKaryawan as $kar)
                                                <div class="bg-gray-100 text-sm text-center p-2 rounded shadow">
                                                    {{ $kar->detail->user->nama_lengkap ?? $kar->nama_lengkap }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">Belum ada karyawan.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-right">
                            <button @click="openModal = false" class="" hidden></button>
                        </div>
                    </div>
                </div>


            </div>
        @endif

        <!-- Grafik dan Top Karyawan -->
        <div class="row mb-4">
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class='card-heading p-1 pl-3'>Rata-Rata Nilai Karyawan per Bulan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="pl-3">
                                    <h1 class='mt-5'>Nilai Tertinggi {{ $nilaiTertinggi }}</h1>
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

            <div class="col-md-4 mb-3">
                <div class="card widget-todo">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="card-title d-flex">
                            <i class='bx bx-check font-medium-5 pl-25 pr-75'></i>Top Karyawan
                        </h4>
                    </div>
                    <div class="card-body px-0 py-1" x-data="{ selectedDivisi: 'all' }">
                        <!-- Dropdown untuk memilih divisi -->
                        <div class="mb-4">
                            <label for="divisiFilter" class="block text-gray-700 font-semibold mb-2">Pilih
                                Divisi</label>
                            <select id="divisiFilter" class="form-select block w-full p-2 border rounded-lg"
                                x-model="selectedDivisi">
                                <option value="all">Semua Karyawan</option>
                                @foreach ($divisiList as $divisi)
                                    <option value="{{ $divisi }}">{{ $divisi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tabel Karyawan Berdasarkan Divisi -->
                        <table class="table table-borderless">
                            @foreach ($topKaryawan as $index => $item)
                                @php
                                    $warnaList = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info'];
                                    $warna = $warnaList[array_rand($warnaList)];
                                @endphp
                                <tr
                                    x-show="selectedDivisi === 'all' || selectedDivisi === '{{ $item->karyawan->divisi->nama_divisi ?? '' }}'">
                                    <td class="col-3">
                                        {{ $item->karyawan->user->nama_lengkap ?? '-' }}
                                        - {{ $item->karyawan->divisi->nama_divisi ?? 'Tidak Ada Divisi' }}
                                    </td>
                                    

                                    <td class="col-6">
                                        <div class="progress">
                                            <div class="progress-bar {{ $warna }}" role="progressbar"
                                                style="width: {{ min($item->rata_rata, 100) }}%;"
                                                aria-valuenow="{{ $item->rata_rata }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ number_format($item->rata_rata, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-3 text-center">{{ number_format($item->rata_rata, 2) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- Alpine.js script untuk menangani pemilihan divisi -->
                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('divisiFilter', () => ({
                                selectedDivisi: 'all', // Defaultnya adalah 'all'
                            }));
                        });
                    </script>

                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
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
