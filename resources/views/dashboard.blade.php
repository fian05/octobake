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
        document.addEventListener('DOMContentLoaded', function () {
            var grafikHarian = @json($dataHarian);
            grafikHarian.reverse();
            grafikHarian = grafikHarian.slice(-5);
            var labelHarian = [];
            var dataHarian = [];
            var hariIni = new Date();

            for (var i = 0; i < grafikHarian.length; i++) {
                var item = grafikHarian[i];
                if (item) {
                    var dateItem = new Date(item.tanggal.replace(/-/g, "/"));
                    labelHarian.push(dateItem.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' }));
                    dataHarian.push(item.total_pembelian);
                } else {
                    labelHarian.push(hariIni.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' }));
                    dataHarian.push(0);
                }
                hariIni.setDate(hariIni.getDate() - 1);
            }
            var chartData = {
                Harian: {
                    labels: labelHarian,
                    data: dataHarian,
                },
                Mingguan: {
                    labels: '',
                    data: [100, 120, 90, 105, 110],
                },
                Bulanan: {
                    labels: '',
                    data: [350, 420, 390, 460, 510],
                }
            };

            var ctx = document.getElementById('grafikPenjualan').getContext('2d');
            var grafikPenjualan;

            grafikPenjualan = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData['Harian'].labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: chartData['Harian'].data,
                        backgroundColor: '#f0ca78',
                        borderColor: '#f7aa02',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctx = document.getElementById('grafikPenjualan').getContext('2d');
            var grafikPenjualan;

            var dataTypeSelect = document.getElementById('data-type');

            dataTypeSelect.value = "Harian";

            dataTypeSelect.addEventListener('change', function () {
                var selectedType = dataTypeSelect.value;

                if (grafikPenjualan) {
                    grafikPenjualan.destroy();
                }

                // Inisialisasi grafik dengan data yang dipilih
                grafikPenjualan = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData[selectedType].labels,
                        datasets: [{
                            label: 'Penjualan',
                            data: chartData[selectedType].data,
                            backgroundColor: '#f0ca78',
                            borderColor: '#f7aa02',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection