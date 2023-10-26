@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('head')
    <script src="{{ asset('js/plugins/chart.js/chart2.js') }}"></script>
@endsection

@section('content')
    <main id="main-container">
        <div class="bg-image overflow-hidden" style="background-image: url({{ asset('media/photos/photo3@2x.jpg') }});">
            <div class="bg-primary-dark-op">
                <div class="content content-full">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mt-5 mb-2 text-center text-sm-start">
                        <div class="flex-grow-1">
                            <h1 class="fw-semibold text-white mb-0">Dashboard</h1>
                            <h2 class="h4 fw-normal text-white-75 mb-0">Selamat Datang, {{ Auth::user()->nama }} !</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Grafik Penjualan</h3>
                    <div class="block-options space-x-1">
                        <select id="data-type" class="form-select form-select-sm">
                            <option value="Harian">Harian</option>
                            <option value="Mingguan">Mingguan</option>
                            <option value="Bulanan">Bulanan</option>
                        </select>
                    </div>                    
                </div>
                <div class="block-content block-content-full">
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="grafikPenjualan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        function generateRealTimeData(dataType) {
            const now = new Date();
            const labels = [];
            const data = [];

            if (dataType === "Harian") {
                const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                for (let i = 4; i >= 0; i--) {
                    const date = new Date(now);
                    date.setDate(now.getDate() - i);
                    const formattedDate = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    const formattedTimestamp = date.toISOString().slice(0, 10);
                    const dayOfWeek = daysOfWeek[date.getDay()];
                    var dataHarian = {!! $dataHarianJson !!};
                    const matchingData = dataHarian.find(item => item.tanggal_pembelian === formattedTimestamp);

                    labels.push(`${dayOfWeek}, ${formattedDate}`);
                    data.push(matchingData ? matchingData.total : 0);
                }
            } else if (dataType === "Mingguan") {
                var dataMingguan = {!! $dataMingguanJson !!};
                for (let i = 0; i < 5; i++) {
                    const startDate = new Date(now);
                    startDate.setDate(now.getDate() - now.getDay() - (7 * i)); // Menghitung Senin
                    const endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + 6); // Menghitung Minggu

                    // Format label dengan memeriksa apakah tahun dan bulan sama
                    const startYear = startDate.getFullYear();
                    const endYear = endDate.getFullYear();
                    const startMonth = startDate.getMonth();
                    const endMonth = endDate.getMonth();

                    let label = startDate.toLocaleDateString('id-ID', { day: 'numeric' });

                    if (startYear !== endYear) {
                        label += ` ${startDate.toLocaleDateString('id-ID', { month: 'long' })} ${startYear}`;
                    } else if (startMonth !== endMonth) {
                        label += ` ${startDate.toLocaleDateString('id-ID', { month: 'long' })}`;
                    }

                    label += ` - ${endDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long' })} ${endYear}`;

                    labels.unshift(label);

                    // Cari data penjualan mingguan sesuai rentang tanggal
                    const matchingData = dataMingguan.find(item => item.tanggal_pembelian === startDate.toISOString().slice(0, 10));

                    data.unshift(matchingData ? matchingData.total_penjualan : 0);
                }
            } else if (dataType === "Bulanan") {
                for (let i = 4; i >= 0; i--) {
                    const date = new Date(now);
                    date.setMonth(now.getMonth() - i);
                    labels.push(date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }));
                    // Gantilah ini dengan logika untuk mendapatkan data penjualan bulanan sesuai tanggal
                    data.push(Math.floor(Math.random() * 50000));
                }
            }

            return { labels, data };
        }

        const ctx = document.getElementById("grafikPenjualan").getContext('2d');
        let currentDataType = "Harian";

        // Inisialisasi chart dengan data harian sebagai default
        const initialData = generateRealTimeData(currentDataType);
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialData.labels,
                datasets: [{
                    label: 'Total Pendapatan',
                    data: initialData.data,
                    backgroundColor: '#f0ca78',
                    borderColor: '#f7aa02',
                    borderWidth: 1,
                    fill: true,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + new Intl.NumberFormat('id-ID', {
                                    style: 'decimal',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp' + new Intl.NumberFormat('id-ID', {
                                    style: 'decimal',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(context.parsed.y);
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Fungsi untuk mengganti data grafik saat jenis data dipilih
        document.getElementById("data-type").addEventListener("change", function () {
            currentDataType = this.value;
            const newData = generateRealTimeData(currentDataType);
            chart.data.labels = newData.labels;
            chart.data.datasets[0].data = newData.data;
            chart.update();
        });
    </script>
@endsection